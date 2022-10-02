const cacheContainer = "static_v2";
const files = [
    "./",
    "./index.html",
    "./level.html",
    "./css/style.css",
    "./Levels.json",
    "./SortIT_logo.png",
    "./manifest.json",
    "./favicon.ico",
    "./Sortable.min.js"
]


self.addEventListener('install', function(event){
    event.waitUntil(
        caches.open(cacheContainer)
            .then(cache => {
                cache.addAll(files);
            })
    )
})

self.addEventListener("fetch", function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response){
        if (response) {
            return response;
        }
    })
    )
})

self.addEventListener('activate', function(event){
    console.log("service worker activated", event);
})
// self.addEventListener('install', function(event){
//     console.log("service worker installed", event);
// })
//
// self.addEventListener('activate', function(event){
//     console.log("service worker activated", event);
// })