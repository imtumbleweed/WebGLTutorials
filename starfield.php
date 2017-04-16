<!--

    CC3.0 Distribution License

    NOTE: This software is released under CC3.0 creative commons
    If using in own projects please give credit to the original author,

    By linking to:

    http://www.webgltutorials.org

    Thanks :-)

//--><html>
    <head>
        <title>Starfield Demo</title>
    </head>
    <body>
        <canvas id = "canvas" width = "800" height = "500"></canvas>
        <script>

            var ScreenWidth = 800;
            var ScreenHeight = 500;

            var MAX_DEPTH = 10;

            class Star {

                constructor() {

                    this.reset = function()
                    {
                        this.x = 1 - Math.random() * 2.0;
                        this.y = 1 - Math.random() * 2.0;
                        this.z = Math.random() * -MAX_DEPTH;
                        this.x2d = 0;
                        this.y2d = 0;
                        this.angle = 0.001;
                    }

                    this.project = function()
                    {
                        // Rotate this star around the Z axis using trigonometry (sine & cosine)
                        this.x = this.x * Math.cos(this.angle) - this.y * Math.sin(this.angle);
                        this.y = this.y * Math.cos(this.angle) + this.x * Math.sin(this.angle);
                    
						// Calculate "Camera View" -- Project the 3D star coordinates to the 2D screen
                        this.x2d = (ScreenHeight/ScreenWidth) * ScreenWidth * this.x / this.z + (ScreenWidth / 2);
                        this.y2d = ScreenHeight * this.y / this.z + (ScreenHeight / 2);
                        
//                        this.x2d = ScreenWidth * this.x / this.z + (ScreenWidth / 2);
                        this.y2d = ScreenHeight * this.y / this.z + (ScreenHeight / 2);
                         
                    	// Move star toward the camera
                        this.z += 0.0025;

                        // Reset this star if it goes outside of the viewing area
                        if (this.x2d <= 0 || this.x2d >= ScreenWidth ||
                            this.y2d <= 0 || this.y2d >= ScreenHeight)
                            this.reset();
                    }

                    this.draw = function()
                    {
                        var star_size = 3 - (-this.z / 2);
                        var star_color = (MAX_DEPTH + this.z) / (MAX_DEPTH*2);

                        window.gfx.globalAlpha = star_color;
                        window.gfx.fillStyle = 'white';
                        window.gfx.fillRect(this.x2d, this.y2d, star_size, star_size);
                        window.gfx.globalAlpha = 1;
                    }

                    // Reset (initialize) on object construction
                    this.reset();
                }
            }
            
            // Initialize stars

            var STARS_MAX = 2000;

            var stars = new Array(STARS_MAX);

            for (let i = 0; i < STARS_MAX; i++)
                stars[i] = new Star();

			// Create and initialize canvas
            var canvas = document.getElementById("canvas");
            var context = window.gfx = canvas.getContext('2d');

            // Main animation loop
            setInterval(function() {

                // Clear screen
                gfx.beginPath();
                    gfx.fillStyle = 'black';
                    gfx.rect(0, 0, 800, 500);
                gfx.fill();

				// Move and draw stars
                gfx.beginPath();
                    gfx.fillStyle = 'white';
                    for (let i = 0; i < STARS_MAX; i++) {
                        stars[i].project();
                        stars[i].draw();
                    }
                gfx.fill();

            }, 0);
            
        </script>
    </body>
</html>