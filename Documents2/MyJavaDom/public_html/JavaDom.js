/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



var html = document.getElementsByTagName("html")[0];


var tag = document.createElement("p");
var text = document.createTextNode("<h1>Drew is the best e-learning platform</h1>");
tag.appendChild(text);
html.appendChild(tag);


window.window.alert('stuff');

var mydoc = new tg("p");
html.appendChild(mydoc.build());




class tg {
    mTag;
    mTagName;
    mAttributes = {};
    mChildTags = [];

    constructor(tagName) {
        mTag = document.createElement(tagName);
        mTagName = tagName;
    }

    addAttribute(key, value) {
        mAttributes[key] = value;
    }

    addChildTag() {

    }

    addText(text) {
        mChildTags.push(text);
    }


    // only call for root node
    get node() {
        return mTag;
    }
}



