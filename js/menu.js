function hasAnyGrandchildren(tree) {
    let newTree = []
    let length = tree.length

    for (let i = 0; i < length; i++) {
        const node = tree[i]
        let hasGrandchildren = false
        if (node.children) {
            let children = this.hasAnyGrandchildren(node.children)
            children.forEach(child => {
                if (child.children && child.children.length > 0) {
                    hasGrandchildren = true
                }
            })
        }
        node.hasGrandchildren = hasGrandchildren
        newTree.push(node)

    }
    return newTree
}

function createTree() {
    fetch(histology_directory.data_directory + '/results.json') //histology_directory.data_directory+'/results.json'
        .then(result => {
            result.json().then(json => {

                function parseTree(nodes, parentID) {
                    let tree = []
                    let length = nodes.length
                    for (let i = 0; i < length; i++) {
                        let node = nodes[i]
                        if (node.post_parent == parentID) {
                            let children = parseTree(nodes, node.ID)

                            if (children.length > 0) {
                                node.children = children
                            }
                            tree.push(node)
                        }
                    }

                    return tree
                }

                const completeTree = parseTree(json, "0")
                const annotatedTree = this.hasAnyGrandchildren(completeTree)
                this.tree = annotatedTree
                //console.log(annotatedTree)
                publishTree(annotatedTree)
                return annotatedTree
            })
        })
}


function publishTree(tree) {
    var menu = ''
    tree.forEach(function(item) {
        //console.log(item)   
        if (item.hasGrandchildren === true) {
            menu = menu.concat('<li><h2>' + item.post_title) + '</h2>'
            menu = menu.concat('<div class="cell-main-index">')
            menu = menu.concat(makeLimb(item.children, 'childbearing top'))
            menu.concat('</li>')
            menu = menu.concat('</div>')
            limbMenu = ''
        }

    })
    jQuery(menu).appendTo("#app ul");
    stunLinks();
    checkUrl();
    specialAddition();
}

var limbMenu = ''


function makeLimb(data, type) {
    limbMenu = limbMenu.concat('<ul>')
    data.forEach(function(item) {
        if (item.hasGrandchildren === true) {
            limbMenu = limbMenu.concat('<li><a id="menu_' + item.ID + '" class="' + type + '" href="' + item.guid + '">' + overviewClean(item.post_title) + ifParent(item.hasGrandchildren) + '</a>')
            makeLimb(item.children, "childbearing")
            limbMenu = limbMenu.concat('</li>')
        }
        if (item.children && !item.hasGrandchildren) {
            limbMenu = limbMenu.concat('<li><a class="live" href="' + item.children[0].guid + '">' + overviewClean(item.post_title) + '</a>')
            makeLimb(item.children, "live")
        } //this is super ugly but this appears to be the only item that violates the pattern
        if (item.post_title == "Overview of connective tissues" || item.post_title == "Comparison") {
            //console.log(item.post_title + ' foo')
            limbMenu = limbMenu.concat('<li><a class="live" href="' + item.guid + '">' + overviewClean(item.post_title) + '</a>')
        }
    })
    limbMenu = limbMenu.concat('</ul>')
    return limbMenu
}



function ifParent(kids) {
    if (kids === true) {
        return '<i class="fa fa-arrow-right"></i>'
    } else {
        return ""
    }
}


createTree();

function overviewClean(title) {
    var regex = /overview/i;
    var found = title.match(regex)
    if (found === null) {
        return title
    } else {
        return title.substring(0, 8)
    }
}

function stunLinks() {
    jQuery(".childbearing").click(function(e) {
        e.preventDefault();
        jQuery('.active').removeClass('active');
        jQuery(this).parent().children('ul').toggleClass('active');
        jQuery(this).parentsUntil('.cell-main-index').addClass('active');
        updateURL(jQuery(this)["0"].id)
    });
}



function checkUrl() {
    var id = getQueryVariable("menu");
    //console.log(id)
    //console.log('menu thing')
    if (id) {
        jQuery('#' + id).parent().children('ul').addClass('active');
        //console.log(jQuery('#'+id));
        //console.log('foo')
        jQuery('#' + id).parents().addClass('active');
    }
}

//from https://css-tricks.com/snippets/javascript/get-url-variables/
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
}


function specialAddition() {
    if (document.getElementById('menu_325')) {
        var exocrine = document.getElementById('menu_325')
        var parent = exocrine.parentElement.parentElement

        var node = document.createElement('li'); // Create a <li> node
        var a = document.createElement('a'); // Create a text node
        a.setAttribute('href', '?menu=menu_212');
        a.textContent = 'Endocrine ';
        node.appendChild(a); // Append the text to <li>
        parent.appendChild(node);
        a.innerHTML = a.innerHTML + '<i class="fa fa-arrow-right"></i>'
    }

}


//from https://eureka.ykyuen.info/2015/04/08/javascript-add-query-parameter-to-current-url-without-reload/
function updateURL(id) {
    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?menu=' + id;
        window.history.pushState({ path: newurl }, '', newurl);
    }
}


//modal stuff
const modal = document.querySelector('#vidModal');
const modalButton = document.querySelector('#videoPlayer');
const close = document.getElementsByClassName("close")[0];


modalButton.onclick = function() {
  modal.style.display = "block";
}

close.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}