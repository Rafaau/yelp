try {
    const tx = document.getElementsByTagName("textarea");
    for (let i = 0; i < tx.length; i++) {
      tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
      tx[i].addEventListener("input", OnInput, false);
    }
    
    function OnInput() {
      this.style.height = 0;
      this.style.height = (this.scrollHeight) + "px";
    }
} catch (error) {}

try {
    document.getElementById('search-btn').addEventListener('click', function() {
        var cflt = document.getElementById('cflt-input').value;
        var location = document.getElementById('loc-input').value;
        window.location.href = `../search?cflt=${encodeURIComponent(cflt)}&find_loc=${encodeURIComponent(location)}`;
    });
} catch (error) {}

try {
    document.getElementById('write-review-btn').addEventListener('click', function() {
        var cflt = document.getElementById('cflt-input').value;
        var location = document.getElementById('loc-input').value;
        window.location.href = `../search?cflt=${encodeURIComponent(cflt)}&find_desc=writereview&find_loc=${encodeURIComponent(location)}`;
    });
} catch (error) {}

try {
    document.getElementById('see-hours-button').addEventListener('click', function() {
        document.getElementById('hours').scrollIntoView({behavior: "smooth"});
    });
} catch (error) {}

try {
    var reviewStars = document.getElementById('review-stars');
    var hovered = 0;
    var chosen = 0;

    function appendStars() {
        if (reviewStars.hasChildNodes()) {
            reviewStars.innerHTML = '';
        }

        var color =
            chosen >= 9  ? 'rgba(251,67,60,1)' :
            chosen >= 7 ? 'rgba(255,100,61,1)' :
            chosen >= 5 ? 'rgba(255,135,66,1)' :
            chosen >= 3 ? 'rgba(255,173,72,1)' :
            chosen >= 1 ? 'rgba(255,204,75,1)' : 
            '#BBBAC0'
            


        if (hovered > 0) {
            color =
            hovered >= 9 ? 'rgba(251,67,60,1)' :
            hovered >= 7 ? 'rgba(255,100,61,1)' :
            hovered >= 5 ? 'rgba(255,135,66,1)' :
            hovered >= 3 ? 'rgba(255,173,72,1)' :
            hovered >= 1 ? 'rgba(255,204,75,1)' : 
            '#BBBAC0'
        }

        for (var i = 1; i <= 5; i++) {
            var firstHalf = chosen >= i * 2 - 1 ? true : false
            var secondHalf = chosen >= i * 2 ? true : false

            if (hovered > 0) {        
                firstHalf = hovered >= i * 2 - 1 ? true : false
                secondHalf = hovered >= i * 2 ? true : false
            }

            var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.setAttributeNS(null, "width", "30");
            svg.setAttributeNS(null, "height", "30");
            svg.setAttributeNS(null, "viewBox", "0 0 20 20");
            svg.setAttributeNS(null, "class", "mr-1 cursor-pointer");

            var path1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path1.setAttributeNS(null, "fill", firstHalf ? color : '#BBBAC0');
            path1.setAttributeNS(null, "opacity", "1");
            path1.setAttributeNS(null, "d", "M0 4C0 1.79086 1.79086 0 4 0H10V20H4C1.79086 20 0 18.2091 0 16V4Z");
            path1.addEventListener('mouseenter', (function(index) {
                return function() {
                    if (hovered != index * 2 - 1) {
                        hovered = index * 2 - 1;
                        appendStars();
                    }
                }
            })(i));
            path1.addEventListener('click', (function(index) {
                return function() {
                    chosen = index * 2 - 1;
                    appendStars();
                    var starsInput = document.getElementById('review_form_stars')
                    starsInput.setAttributeNS(null, "value", `${chosen}`);
                }
            })(i));

            var path2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path2.setAttributeNS(null, "fill", secondHalf ? color : '#BBBAC0');
            path2.setAttributeNS(null, "opacity", "1");
            path2.setAttributeNS(null, "d", "M20 4C20 1.79086 18.2091 0 16 0H10V20H16C18.2091 20 20 18.2091 20 16V4Z");
            path2.addEventListener('mouseenter', (function(index) {
                return function() {
                    if (hovered != index * 2) {
                        hovered = index * 2;
                        appendStars();
                    }
                }
            })(i));
            path2.addEventListener('click', (function(index) {
                return function() {
                    chosen = index * 2;
                    appendStars();
                    var starsInput = document.getElementById('review_form_stars')
                    starsInput.setAttributeNS(null, "value", `${chosen}`);
                }
            })(i));

            var path3 = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path3.setAttributeNS(null, "fill", 'white');
            path3.setAttributeNS(null, "opacity", "1");
            path3.setAttributeNS(null, "d", "M10 13.3736L12.5949 14.7111C12.7378 14.7848 12.9006 14.8106 13.0593 14.7847C13.4681 14.718 13.7454 14.3325 13.6787 13.9237L13.2085 11.0425L15.2824 8.98796C15.3967 8.8748 15.4715 8.72792 15.4959 8.569C15.5588 8.15958 15.2779 7.77672 14.8685 7.71384L11.983 7.2707L10.6699 4.66338C10.5975 4.51978 10.481 4.40322 10.3374 4.33089C9.96742 4.14458 9.51648 4.29344 9.33017 4.66338L8.01705 7.2707L5.13157 7.71384C4.97265 7.73825 4.82577 7.81309 4.71261 7.92731C4.42109 8.22158 4.42332 8.69645 4.71759 8.98796L6.79152 11.0425L6.32131 13.9237C6.29541 14.0824 6.3212 14.2452 6.39486 14.3881C6.58464 14.7563 7.03696 14.9009 7.40514 14.7111L10 13.3736Z");

            svg.appendChild(path1);
            svg.appendChild(path2);
            svg.appendChild(path3);   
            
            reviewStars.appendChild(svg);
        }
    }

    reviewStars.addEventListener('mouseleave', function() {
        hovered = 0;
        appendStars();
    });

    appendStars();
} catch (error) {}

try {
    document.querySelectorAll('.reaction-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var reaction = {};
            reaction[this.dataset.reaction] = this.dataset.reactionCount;

            var button = this;

            fetch('/reviews/update_reaction', {
                method: 'POST',
                body: JSON.stringify({
                    id: this.dataset.reviewId,
                    reactions: reaction
                })
            }).then(function(response) {
                if (response.ok) {
                    var reactionCountElement = button.querySelector('.reaction-count');
                    reactionCountElement.textContent = reaction[button.dataset.reaction];
                }
            });
        });
    });
} catch (error) {}

try {
    document.querySelectorAll('div.bg-zinc-200 button').forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementsByClassName('logo')[0].scrollIntoView({behavior: "smooth"});
        });
    });
} catch (error) {}    





