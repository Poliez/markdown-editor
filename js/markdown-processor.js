function MarkdownProcessor(){
    "use strict";

    function handleImages(text) {
    	var regex = /!\[([^\]]*?)]\((.*?)\)/g;

    	text = text.replace(regex, function(wholeMatch, altText, url){
    		return "<img src='" + url + "' alt='" + altText + "'/>";
    	});

    	return text;
    }

    function handleLinks(text) {
    	var regex = /\[([^\]]*?)]\((.*?)\)/g;

    	text = text.replace(regex, function(wholeMatch, text, url){
    		return "<a class='marked' href='" + url + "'>" + text + "</a>";
    	});

    	return text;
    }

    function handleHeaders(text) {
    	var regex = /^(#{1,6})[ \t]*(.+?)[ \t]*\n/gm;

    	text = text.replace(regex, function(wholeMatch, hashes, headerText) {
    		var type = hashes.length;
    		return "<h" + type + " class='marked'>" + headerText + "</h" + type + ">";
    	});

    	return text;
    }

    function handleEmphasizedAndStrong(text) {
    	var bothRegex = /\*\*\*(?![\s])(.*?)\*\*\*/gm;
    	var strongRegex = /\*\*(?![\s])(.*?)\*\*/gm;
    	var emphasizedRegex = /\*(?![\s])(.*?)\*/gm;

    	text = text.replace(bothRegex, function(wholeMatch, text) {
    		return "<strong><em>" + text + "</em></strong>";
    	});

    	text = text.replace(strongRegex, function(wholeMatch, text) {
    		return "<strong>" + text + "</strong>";
    	});

    	text = text.replace(emphasizedRegex, function(wholeMatch, text) {
    		return "<em>" + text + "</em>";
    	});

    	return text;
    }

    function handleOrderedLists(text) {
    	var orderedLiRegex = /\d\.[ ](.*)/gm;
    	var wrapLiRegex = /(<li class='marked inol'>.*?<\/li>\n)+/gm;

    	text = text.replace(orderedLiRegex, function(wholeMatch, listElem) {
    		return "<li class='marked inol'>" + listElem + "</li>";
    	});

    	text = text.replace(wrapLiRegex, function(wholeList, lastListElem) {
    		return "<ol class='marked'>" + wholeList + "</ol>";
    	});

    	return text;
    }

    function handleUnorderedLists(text) {
		var unorderedLiRegex = /\*[ ](.*)/gm;
    	var wrapLiRegex = /(<li class='marked inul'>.*?<\/li>\n)+/gm;

    	text = text.replace(unorderedLiRegex, function(wholeMatch, listElem) {
    		return "<li class='marked inul'>" + listElem + "</li>";
    	});

    	text = text.replace(wrapLiRegex, function(wholeList, lastListElem) {
    		return "<ul class='marked'>" + wholeList + "</ul>";
    	});

    	return text;
    }

    function handleParagraphs(text) {
    	var paragraphRegex = /^(?!\*[ ])(?!\d\.[ ])(?!```)(?!<br\/>)[^\n#>].*$/gm;

    	text = text.replace(paragraphRegex, function(paragraph) {
    		return "<p class='marked'>" + paragraph + "</p>";
    	});

    	return text;
    }

    function handlePreformatted(text) {
        var preRegex = /```\n([\d\D]*)\n```/gm;

        text = text.replace(preRegex, function(wholeMatch, text) {
            text = text.replace(/<p class='marked'>/gm, "");
            text = text.replace(/<\/p>/gm, "");
            return "<div class='pre-container'><pre class='marked'>" + text + "</pre></div>";
        });

        return text;
    }

    function handleInlineCode(text) {
        var inlineCodeRegex = /`(.*?)`/gm;

        text = text.replace(inlineCodeRegex, function(wholeMatch, text) {
            return "<code class='marked'>" + text + "</code>";
        });

        return text;
    }

    function handleBlockQuote(text) {
        var blockQuoteRegex = /^>(.*)/gm;

        text = text.replace(blockQuoteRegex, function(wholeMatch, text) {
            return "<blockquote class='marked'><p class='marked'>" + text + "</p></blockquote>";
        });

        return text;
    }

    this.convertToHtml = function (text) {
		if (!text) {
			return text;
		}

		text = text.replace(/\r\n/g, "\n"); // Windows --> Unix
		text = text.replace(/\r/g, "\n"); // MacOS --> Unix
		text = text.replace(/\n{2,}/g, "\n<br/>\n");

		text = handleParagraphs(text);

		text = handleImages(text);
		text = handleLinks(text);
		text = handleHeaders(text);
		text = handleEmphasizedAndStrong(text);
		text = handleOrderedLists(text);
		text = handleUnorderedLists(text);
        text = handlePreformatted(text);
        text = handleInlineCode(text);
        text = handleBlockQuote(text);

		return "<div class='marked'>" + text + "</div>";
	};
}
