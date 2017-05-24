<!doctype html>
<html>
<head>
    <title>Tutorial 14 - Dynamic Values (RGB)</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js?v=6'></script>
    <script src = 'http://www.tigrisgames.com/fx/primitives.js?v=2'></script>
    <script src = 'http://www.tigrisgames.com/fx/texture.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/vector3.js?v=1'></script>
    <script src = 'http://www.tigrisgames.com/fx/matrix.js?v=2'></script>
    <script src = 'http://www.tigrisgames.com/fx/collision.js?v=3'></script>
    <script src = 'http://www.tigrisgames.com/fx/ply-multi.js?v=1'></script>
    <script src = 'http://www.tigrisgames.com/fx/model.js?v=4'></script>
    <script src = 'http://www.tigrisgames.com/fx/keyboard.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/mouse.js'></script>

    <!--<script src = 'http://www.tigrisgames.com/fx/collision.js'></script>//-->
    <script type = "text/javascript">

        /* -- Gl functions -- */

        var canvas = null;
        var gl = null;

        var model_indices = null;

        var car_angle = 0;

        $(document).ready(function() {

            var canvas = document.getElementById('gl');

            gl = GetWebGLContext(canvas);

            if (!gl)
                console.log('Failed to set up WebGL.');

            else { // Load a shader from "shaders" folder

                // Enable depth test
                gl.enable(gl.DEPTH_TEST);
                gl.depthFunc(gl.LESS);

                InitializeKeyboard();

                InitializeMouse();

                CreateShadersFromFile( gl );

                LoadTextures();

                LoadModels();
            }
        });

        var x = 0;
        var y = 0;
        var z = 0;


        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All WebGL shaders have finished loading!");

            for (var i = 0; i < 1; i++)
                BindModel( i );

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.lightProgram );

            // Create storage for our matrices
            var Projection = new CanvasMatrix4();
            var ModelView = new CanvasMatrix4();
            var ObserverView = new CanvasMatrix4();

            var Model = new CanvasMatrix4();
            var View = new CanvasMatrix4();

            var model_angle = -150;

            // Size of our canvas
            var width = 800;
            var height = 600;

            $("#gl").css( { "width" : width + "px", "height" : height + "px" } );

            // Start main drawing loop
            var T = setInterval(function() {

                if (!gl)
                    return;

                if (key.left) x -= 0.01;
                if (key.right) x += 0.01;
                if (key.up) y += 0.01;
                if (key.down) y -= 0.01;

                var scale = 0.5;

                // Clear WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);

                gl.clear(gl.COLOR_BUFFER_BIT);
                gl.clear(gl.DEPTH_BUFFER_BIT);

                // Set "brick.png" as active texture to pass into the shader
                gl.activeTexture(gl.TEXTURE0);
                gl.bindTexture(gl.TEXTURE_2D, road.texture);
                gl.uniform1i(gl.getUniformLocation(Shader.lightProgram, 'image'), 0);

                // Indices of cube
                // var indices_cube = window.ref_arrayMDL[1][4];



                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.lightProgram, "Projection"), false, Projection.getAsFloat32Array());


                var View = new CanvasMatrix4();

                // Generate view matrix
                View.makeIdentity();
                View.translate(0, -0.5, -3);

                car_angle += 1;

                // Set viewport to Upper Left corner
                gl.viewport(0, 0, width/2, height/2);

                // Purple cars
                rgb = [1.0, 0.0, 1.0];
                for (var xx = 0; xx < 5; xx++) {
                    for (var yy = 0; yy < 5; yy++) {
                        render_spinning_cars(View, xx, yy, rgb);
                    }
                }

                gl.viewport(width/2, 0, width/2, height/2);

                // Green cars
                rgb = [0.0, 1.0, 0.0];
                for (var xx = 0; xx < 5; xx++) {
                    for (var yy = 0; yy < 5; yy++) {
                        render_spinning_cars(View, xx, yy, rgb);
                    }
                }

                gl.viewport(width/2, height/2, width/2, height/2);

                // Blue cars
                rgb = [0.0, 0.0, 1.0];
                for (var xx = 0; xx < 5; xx++) {
                    for (var yy = 0; yy < 5; yy++) {
                        render_spinning_cars(View, xx, yy, rgb);
                    }
                }

                gl.viewport(0, height/2, width/2, height/2);

                // Yellow cars
                rgb = [1.0, 1.0, 0.0];
                for (var xx = 0; xx < 5; xx++) {
                    for (var yy = 0; yy < 5; yy++) {
                        render_spinning_cars(View, xx, yy, rgb);
                    }
                }

            });
        }

        function render_spinning_cars(View, xx, yy, rgb)
        {
            var Model = new CanvasMatrix4();
            // Generate model matrix
            Model.makeIdentity();
            Model.rotate(car_angle, 0, 1, 0);
            Model.translate(-1.29 + xx * 0.65, -1.05 + yy * 0.6, 0);
            gl.uniform3fv(gl.getUniformLocation(Shader.lightProgram, "rgb"), rgb);
            gl.uniformMatrix4fv(
                gl.getUniformLocation(Shader.lightProgram, "Model"), false,
                Model.getAsFloat32Array());
            gl.uniformMatrix4fv(
                gl.getUniformLocation(Shader.lightProgram, "View"), false,
                View.getAsFloat32Array());
            gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);
        }

    </script>
</head>
<style>
    #fx { position: relative; margin: 0 auto; width: 1000px; height: 500px; border: 1px solid gray; }
    #gl { width: 800px; height: 600px; }
</style>
<body style = "background: #202020; padding: 32px;">
<canvas id = "gl" width = "800" height = "600"></canvas>
</body>
</html>