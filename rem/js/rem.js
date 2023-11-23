document.addEventListener("DOMContentLoaded", ()=>{


    window.onscroll = function() {stickyFunction()};

// Get the header
    if (document.getElementById("myHeader")){
        const header = document.getElementById("myHeader");

// Get the offset position of the navbar
        const sticky = header.offsetTop;
        function stickyFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }

        }
    }


// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position


})


