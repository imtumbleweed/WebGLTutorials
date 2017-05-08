window.ResourceId = 0;
window.TotalTextures = 0;
window.Ltimer = null;

// Do not output sprite name, width & height to console -- if true.
var SilentLoad = false;

// Texture class
var Texture = function(fn) {

    var that = this;
    var root = this;

    this.filename = fn;                     // Image filename path
    this.width    = 0;
    this.height   = 0;

    this.image    = null;                   // JavaScript image
    this.texture  = gl.createTexture();     // Create WebGL texture object

    // Primary image loader function
    this.load = function(filename)  {
        that.image = new Image();
        that.image.onload = function(event) {

            var file = fn.split("/");

            that.width = this.width;
            that.height = this.height;

            gl.bindTexture(gl.TEXTURE_2D, that.texture);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, that.image);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_NEAREST);
            gl.generateMipmap(gl.TEXTURE_2D);
            gl.bindTexture(gl.TEXTURE_2D, null);

            if (!SilentLoad)
                console.log("Loaded texture image (" + that.width + "x" + that.height + ") filename = " + file[file.length-1]);

            window.ResourceId++;   // increase resource counter
        };
        that.image.src = filename; // Assign resource to "src"
        return that;               // Return a link to loaded object
    };

    // Using the function load() above... Load texture, if filename was supplied
    if (fn != undefined && fn != "" && fn != null)
        this.load(fn);
    else
        console.log("Unable to load sprite. Filename '" +
            fn + "' is undefined or null.");
}

function LoadTextures() {

    console.log("LoadTextures(); -- Loading textures");

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() { // Make an HTTP request to load texture images
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
                var msg = xmlhttp.responseText;
                console.log(msg);
                if (JSON.parse(msg) != undefined) {

                    var json = JSON.parse(msg);

                    // Memorize total number of resources -- for progress bar calculation:
                    var resourceNumber = window.TotalTextures = json.length;

                    console.log("window.TotalTextures = " + window.TotalTextures);

                    // Check until all textures are loaded in memory; only then initialize WebGL
                    // Start only if there are textures to load
                    if (window.Ltimer == null && window.TotalTextures != 0) {
                        window.Ltimer = setInterval(function () {
                            if (window.ResourceId >= window.TotalTextures) {

                                console.log("All (" + window.TotalTextures + ") textures loaded");

                                // Now check if shaders have finished loading
                                if (window.ShadersFinishedLoading) {

                                    // Prevent this timer from ticking again after all textures are loaded
                                    clearInterval(window.Ltimer);
                                    window.Ltimer = null;

                                    // Both textures and shaders finished loading;
                                    // Start main rendering loop
                                    window.webGLResourcesLoaded();
                                }
                            }
                        }, 0);
                    }

                    for (var i = 0; i < json.length; i++) {
                        console.log("Loading texture <" + json[i] + ">");
                        var appropriateName = json[i].split(".")[0];
                        window.LoadingFileName = json[i];
                        window[appropriateName] = new Texture("http://localhost/tigrisgames.com/fx/textures/" + window.LoadingFileName);
                    }
                }
            } else console.log("*** unable to open <getTextures.php>");
        }
    }
    xmlhttp.open("GET", "getTextures.php", true);
    xmlhttp.send();
}