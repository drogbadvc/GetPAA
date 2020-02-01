#!/usr/bin/env node
const puppeteer = require('puppeteer');
const argv = require('minimist')(process.argv.slice(2));
const fs = require('fs');

// ========================== Declare Variables ==========================

let time = new Date().toLocaleString("fr-FR",{year: 'numeric', month: '2-digit', day: '2-digit'});
let timestamp = new Date().getTime();
let paas_result = new Array();
let url;

const google_url_fr = 'https://www.google.com/search?hl=fr&gl=FR&ie=utf-8&oe=utf-8&no_sw_cr=1&pws=0&q=';
const google_url_us = 'https://www.google.com/search?hl=en&gl=US&ie=utf-8&oe=utf-8&no_sw_cr=1&pws=0&q=';

const keyword = argv.kw;
const clicks = argv.clicks;
const output = argv.output;
const language = argv.lang;
const help = argv.help;

// ========================== Declare Functions ==========================

async function asyncForEach(array, callback) {
    for (let index = 0; index < array.length; index++) {
      await callback(array[index], index, array);
    }
  }

const waitFor = (ms) => new Promise(r => setTimeout(r, ms));

async function get_paas(browser,keyword,clicks){

    if (language == "en"){
        url = google_url_us+keyword;
    } else {
        url = google_url_fr+keyword;
    }
    
    let pages = await browser.pages();
    const page = pages[0];

    await page.setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36');
    await page.setViewport({
        width: 1920,
        height: 1080
    });

    await page.goto(url, {waitUntil: 'networkidle0'});

    if (clicks == 0) {
        const xpath_expression_question = '//div/div/g-accordion-expander/div[1]/div';

        await page.waitForXPath(xpath_expression_question, {timeout: 1000});
        const questions_without_click = await page.$x(xpath_expression_question);
        const count_questions = questions_without_click.length;

        const questions = await page.evaluate((...questions_without_click) => {

            return questions_without_click.map(e => e.innerText);

        }, ...questions_without_click);

        return {
            questions: questions,
            count: count_questions
        };

    } else if (clicks == 1) {

        const xpath_expression_question = '//div/div/g-accordion-expander/div[1]/div';
        await page.waitForXPath(xpath_expression_question);

        await page.evaluate(() => {document.getElementsByClassName("g kno-kp mnr-c g-blk")[0].scrollIntoView();});

        const questions_without_click = await page.$x(xpath_expression_question);

        let counter = 0;
        if (questions_without_click.length > 0) {
            for(i in questions_without_click) {
                await questions_without_click[i].click();
                await page.waitFor(1000);
                counter++;
            };
        }

        console.log(counter+" questions clicked.");

        const questions_with_1_click = await page.$x(xpath_expression_question);
        const count_questions = questions_with_1_click.length;

        const questions = await page.evaluate((...questions_with_1_click) => {

            return questions_with_1_click.map(e => e.innerText);

        }, ...questions_with_1_click);

        return {
            questions: questions,
            count: count_questions
        };

    } else if (clicks == 2) {

        const xpath_expression_question = '//div/div/g-accordion-expander/div[1]/div';
        await page.waitForXPath(xpath_expression_question);

        await page.evaluate(() => {document.getElementsByClassName("g kno-kp mnr-c g-blk")[0].scrollIntoView();});

        const questions_without_click = await page.$x(xpath_expression_question);

        let counter = 0;
        if (questions_without_click.length > 0) {
            for(i in questions_without_click) {
                await questions_without_click[i].click();
                await page.waitFor(1000);
                counter++;
            };
        }

        console.log(counter+" questions clicked.");
        
        await page.waitFor(1000);
        const questions_with_1_click = await page.$x(xpath_expression_question);
        await page.waitFor(1000);

        await page.evaluate(() => {document.getElementsByClassName("duf3")[1].scrollIntoView();});

        if (questions_with_1_click.length > counter) {
            for(counter; counter < questions_with_1_click.length; counter++) {
                await questions_with_1_click[counter].click();
                await page.waitFor(1000);
            };
        }

        console.log(counter+" questions clicked.");
        
        await page.waitFor(1000);
        const questions_with_2_click = await page.$x(xpath_expression_question);
        const count_questions = questions_with_2_click.length;
        await page.waitFor(1000);

        const questions = await page.evaluate((...questions_with_2_click) => {

            return questions_with_2_click.map(e => e.innerText);

        }, ...questions_with_2_click);

        return {
            questions: questions,
            count: count_questions
        };

    } else if (clicks == "max") {
        // max = 4 rounds if clicks

        const xpath_expression_question = '//div/div/g-accordion-expander/div[1]/div';
        await page.waitForXPath(xpath_expression_question);

        await page.evaluate(() => {document.getElementsByClassName("g kno-kp mnr-c g-blk")[0].scrollIntoView();});

        const questions_without_click = await page.$x(xpath_expression_question);

        let counter = 0;
        if (questions_without_click.length > 0) {
            for(i in questions_without_click) {
                await questions_without_click[i].click();
                await page.waitFor(800);
                counter++;
            };
        } 
        console.log(counter+" questions clicked.");

        await page.waitFor(500);
        const questions_with_1_click = await page.$x(xpath_expression_question);
        await page.waitFor(500);

        await page.evaluate(() => {document.getElementsByClassName("duf3")[1].scrollIntoView();});

        if (questions_with_1_click.length > counter) {
            for(counter; counter < questions_with_1_click.length; counter++) {
                await questions_with_1_click[counter].click();
                await page.waitFor(800);
            };
        }

        console.log(counter+" questions clicked.");
        
        await page.waitFor(500);
        const questions_with_2_click = await page.$x(xpath_expression_question);
        await page.waitFor(500);

        await page.evaluate(() => {document.getElementsByClassName("duf3")[1].scrollIntoView();});

        if (questions_with_2_click.length > counter) {
            for(counter; counter < questions_with_2_click.length; counter++) {
                await questions_with_2_click[counter].click();
                await page.waitFor(800);
            };
        }

        console.log(counter+" questions clicked.");
        
        await page.waitFor(500);
        const questions_with_3_click = await page.$x(xpath_expression_question);
        await page.waitFor(500);

        await page.evaluate(() => {document.getElementsByClassName("duf3")[1].scrollIntoView();});

        if (questions_with_3_click.length > counter) {
            for(counter; counter < questions_with_3_click.length; counter++) {
                await questions_with_3_click[counter].click();
                await page.waitFor(800);
            };
        }

        console.log(counter+" questions clicked.");
        
        await page.waitFor(500);
        const questions_with_4_click = await page.$x(xpath_expression_question);
        const count_questions = questions_with_4_click.length;
        await page.waitFor(500);

        const questions = await page.evaluate((...questions_with_4_click) => {

            return questions_with_4_click.map(e => e.innerText);

        }, ...questions_with_4_click);

        return {
            questions: questions,
            count: count_questions
        };
    }

    await page.close();

};


// ========================== Main Functions ==========================


(async function(){

    if (help) {
        console.log("\n👨‍🎓 How to use the script.\n");
        console.log("You can run the script by typing 'npm run scraper' or 'node get_paas.js' followed by arguments.");
        console.log("Arguments are:");
        console.log(" ♦ '--clicks=[0-2/max]' : how often click on new questions");
        console.log(" ♦ '--kw=[...]' : input of keyword (search term) or 'keywords' for batch mode (read line by line from `keywords.txt`)");
        console.log(" ♦ '--lang=[de/en]' : input of languange");
        console.log(" ♦ '--output=csv' : (optional) to export list of questions");
        console.log(" ♦ '--help' : help");
        console.log("\n📋 Examples:\n");
        console.log("♦ npm run scraper -- --clicks=0 --kw=angela+merkel --lang=de");
        console.log("♦ node get_paas.js --clicks=0 --kw=angela+merkel --lang=de\n");
        console.log("♦ npm run scraper -- --clicks=max --kw=firefox --output=csv --lang=en");
        console.log("♦ node get_paas.js --clicks=max --kw=firefox --output=csv --lang=en");
        console.log("\n☀ For further examples or issues go to https://github.com/jpigla/PAAs-from-SERPs.");
    } else {
        try {
            await puppeteer.launch({
                headless: true,
                ignoreHTTPSErrors: true,
                args: ['--no-sandbox', '--disable-setuid-sandbox'],
            }).then(async browser => {
                if (keyword == "keywords") {
                    let lineReader = require('readline').createInterface({
                        input: require('fs').createReadStream('keywords.txt')
                    });
                    for await (const keywords of lineReader) {
                        const result = await get_paas(browser,keywords,clicks);
                        console.log('');
                        paas_result.push('== Search term: '+keywords+' ('+result.count+') ==');
                        await asyncForEach(result.questions, async (question) => {
                            paas_result.push(question);
                        });
                        paas_result.push('');
                        // paas_result.push('== Number of questions found: '+result.count+' ==');
        
                    };
        
                } else {
        
                    const result = await get_paas(browser,keyword,clicks);
                    
                    console.log('');
                    paas_result.push('== Search term: '+keyword+' ('+result.count+') ==');
                    await asyncForEach(result.questions, async (question) => {
                        paas_result.push(question);
                    });
                    // paas_result.push('== Number of questions found: '+result.count+' ==');
        
                }
        
                paas_result.forEach(question => {
                    console.log(question);
                });
        
                if (output == "csv") {
        
                    let csv_output;
                    let csv_filename = 'paas_'+keyword.replace(" ","+")+'_'+timestamp+'.csv';
        
                    paas_result.pop();
        
                    paas_result.forEach(question => {
                        csv_output += decodeURIComponent(question+'\n');
                    });
        
                    csv_output = csv_output.slice(9);
        
                    fs.writeFile(csv_filename, csv_output, 'utf8', function (err) {
                        if (err) {
                        console.log('Some error occured while trying to save data to CSV!');
                        } else{
                        console.log('\nData was saved successfully to CSV! ('+csv_filename+')');
                        }
                    });
                }
        
                await browser.close();
            });      
        } catch(e) {
            console.log(e)
            process.exit()
        }
    }

})();
