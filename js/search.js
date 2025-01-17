document.getElementById("clear").addEventListener("click", () => {
    let input = document.getElementById('searchInput');

    if (input.value !== "") {
        input.value = "";
        document.getElementById("searchResults").innerHTML = "";
    }
});


document.getElementById('searchInput').addEventListener('keyup', function () {
    var query = this.value.trim();
    if (query !== '') {
        performSearch(query);
    } else {
        document.getElementById("searchResults").innerHTML = "";
    }
});

function performSearch(query) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            displaySearchResults(this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/search.php?searchTerm=" + query, true);
    xmlhttp.send();
}

function displaySearchResults(results) {
    document.getElementById('searchResults').innerHTML = '<p>' + results + '</p>';
}