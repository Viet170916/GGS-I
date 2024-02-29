require('dotenv').config({path: '.env'});

const {
    PlaywrightCrawler, Dataset, ProxyConfiguration,
} = require('crawlee');
const yargs = require("yargs");
const {JSDOM} = require("jsdom");
const {Client} = require('@elastic/elasticsearch');
const {readFileSync} = require("fs");
const {env} = require("crawler/.eslintrc");
// const client = new Client({
//     "node": process.env.ELASTIC_HOST,
//     "auth": {
//         "username": process.env.ELASTIC_USER_NAME,
//         "password": process.env.ELASTIC_PASSWORD,
//     },
//     tls: {
//         ca: readFileSync(process.env.ELASTIC_CA_BUNDLE),
//         // cert: process.env.ELASTIC_CA_BUNDLE
//
//         // "rejectUnauthorized": true
//     },
// });

// const client = new Client({
//     node: 'https://bfaf9781c3854099a0f224420877638c.us-central1.gcp.cloud.es.io', // Elasticsearch endpoint
//     auth: {
//         apiKey: { // API key ID and secret
//             id: process.env.ELASTIC_CLOUD_ID,
//             api_key: process.env.ELASTIC_API_KEY,
//         }
//     }
// })
const client = new Client({
    cloud: { id: process.env.ELASTIC_CLOUD_ID },
    auth: { apiKey: process.env.ELASTIC_API_KEY }
})

// ...
let counter = 0;

function getTextOfElement(element) {
    let text = '';
    for (let node of element.childNodes) {
        if (node.nodeType === 3) {
            text += node.textContent || '';
        }
    }
    return text.trim();
}

function getAltOfImg(element) {
    return element.alt.trim();
}


const crawler = new PlaywrightCrawler({
        requestHandler: async function ({
                                            request, page, enqueueLinks,
                                        }) {
            const newPage = await page.content();
            if (newPage.includes("Không tìm thấy sản phẩm phù hợp")) {
                console.log("empty page");
                return;
            }
            let dom = new JSDOM(await page.content().catch(() => {
            }));
            const DOM = dom.window.document;
            const tagNames = new Set();

            function getAllDataFromEveryATypeOfElement(callback, selector) {
                return Array.from(DOM.getElementsByTagName(selector))
                    .map(element => {
                        return callback(element);
                    }).filter(element => element !== "");
            }
            DOM.querySelectorAll('*').forEach(element => {
                tagNames.add(element.tagName.toLowerCase());
            });
            const data = [...tagNames]
                .filter(tagName => !["script", "html", "style", "noscript", "body", "iframe", "head", "input", "svg"].includes(tagName))
                .map(selector => {
                    return {
                        [selector]: (() => {
                            let term;
                            switch (selector) {
                                case "img":
                                    term = getAllDataFromEveryATypeOfElement(getAltOfImg, selector);
                                    break;
                                default :
                                    term = getAllDataFromEveryATypeOfElement(getTextOfElement, selector);
                                    break;
                            }
                            return term.length > 0 ? term : undefined;
                        })(),
                    };
                }).reduce((result, currentValue) => ({...result, ...currentValue}));
            console.log(++counter, request.loadedUrl);
            await client.index({
                index: 'crawled-pages', id: request.loadedUrl, body: {
                    data,
                    url: request.loadedUrl,
                },
            })
                .catch(error => {
                    console.log(`[${new Date().toUTCString()}]`, error)
                });

            await client.indices.refresh({index: 'crawled-pages'})
                .catch(error => {
                    console.log(`[${new Date().toUTCString()}]`, error)
                })
            await enqueueLinks()
                .catch(() => {
                    console.log("3: error");
                });
        },
        maxRequestsPerCrawl: Number.parseInt(process.env.MAX_REQUEST_EACH_URL),
    })
;
(async () => {
    for (const url of process.env.CRAWLED_ROOT_URLS.split(",")) {
        await crawler.run([url])
            .catch(() => {
                console.log("4: error");
            });
    }
    console.log(`[${new Date().toUTCString()}]`, "end crawler........................");

})();

