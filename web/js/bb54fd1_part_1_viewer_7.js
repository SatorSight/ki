var goToPage;
var addBookmark;

window.addEventListener('load', function () {

    var pages = document.querySelector('#page_variables .pages').innerHTML;
    var currentPage = document.querySelector('#page_variables .currentPage').innerHTML;
    var issueUrl = document.querySelector('#page_variables .issueUrl').innerHTML;
    var issueId = document.querySelector('#page_variables .issueId').innerHTML;
    var tableOfContents = document.querySelector('#page_variables .tableOfContents').innerHTML;
    var endOfContentsNotSubscribed =  document.querySelector('#page_variables .endOfContentsNotSubscribed').innerHTML;

    var view = document.getElementById("view");
    var controls = document.getElementById("controls");

    var menuButton = document.getElementById('menu_button');
    var bookmarksButton = document.getElementById('bookmarks_button');
    var addBookmarkButton = document.getElementById('add_bookmark_button');
    var tocButton = document.getElementById('toc_button');

    ////////////////////////////////////////////////////////////////////////////////////

    addBookmark = function () {

        var pageContainer = $('.page-container');

        var defaultName = pageContainer.find('h1').first()[0] || pageContainer.find('p').first()[0];
        var name = prompt('РќР°Р·РІР°РЅРёРµ Р·Р°РєР»Р°РґРєРё', defaultName.innerText.substring(0, 30));
        if (name) {
            Ajax.setBookmark(issueId, currentPage, name, true, function () {
                var bmMarkup = $('  <div style="padding: 6px 0;" onclick="goToPage(' + currentPage + ')">\
                        <span style="width: 130px; display: inline-block;">' + name + '</span>\
                        <span style="text-align: right; width: 50px;">' + currentPage + '</span>\
                        </div>');
                $('#bookmarks .bookmarks_container').append(bmMarkup);
            });
        }
    };

    goToPage = function (pageNumber) {
        if (pageNumber >= pages.length) pageNumber = pages.length;
        if (pageNumber <= 0) pageNumber = 0;

        window.location.href = issueUrl + "?page=" + (pageNumber);
    };

    //////////////////////////////////////////////////////////////////////////////

    controls.querySelector(".right.arrow").addEventListener('click', function (event) {
        if (currentPage >= pages.length - 1) return;

        currentPage = Number(currentPage);
        window.location.href = issueUrl + "?page=" + (currentPage + 1);
        event.preventDefault();
        return false;
    });

    controls.querySelector(".left.arrow").addEventListener('click', function (event) {
        console.log(endOfContentsNotSubscribed+" "+currentPage+" "+tableOfContents);
        if (currentPage <= 0)return;
        if(+endOfContentsNotSubscribed>0) {
            if (+currentPage > +endOfContentsNotSubscribed){
                window.location.href = issueUrl + "?page=" + tableOfContents;
            }else{
                window.location.href = issueUrl + "?page=" + (currentPage - 1);
            }
        } else {
            window.location.href = issueUrl + "?page=" + (currentPage - 1);
        }
        event.preventDefault();
        return false;
    });

    menuButton.addEventListener('click', function () {
        $('#viewer_menu').slideToggle();
        $('#bookmarks').slideUp();
    });

    bookmarksButton.addEventListener('click', function () {
        $('#bookmarks').slideToggle();
        $('#viewer_menu').slideUp();
    });

    addBookmarkButton.addEventListener('click', function () {
        addBookmark();
        return false;
    });

    tocButton.addEventListener('click', function () {
        goToPage(tableOfContents);
    });

    /////////////////////////////////////////////////////////////////////////////

    if (currentPage >= pages.length)
        controls.querySelector(".right.arrow").style.display = "none";
    if (currentPage <= 1)
        controls.querySelector(".left.arrow").style.display = "none";

    $("img").each(function () {
        var src = $(this).attr('src');
        $(this).on('click', function () {
            window.location.href = src;
        });
    });

    // Ajax.getBookmarks(issueId, function (bookmarks) {
    //     for (var i = 0; i < bookmarks.length; i++) {
    //         var bmMarkup = $('<div style="padding: 6px 0;" onclick="goToPage(' + bookmarks[i].page + ')">\
    //                     <span style="width: 130px; display: inline-block;">' + bookmarks[i].name + '</span>\
    //                     <span style="text-align: right; width: 50px;">' + bookmarks[i].page + '</span>\
    //                     </div>');
    //         $('#bookmarks .bookmarks_container').append(bmMarkup);
    //     }
    // });


});
