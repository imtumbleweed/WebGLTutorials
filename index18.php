<!doctype html>
<html>
<head>
    <title>Tutorial 18 - Per-Vertex Point-Light Light Source Shader</title>
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
    <script src = 'http://www.tigrisgames.com/fx/model4.js?v=5'></script>
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
        var y = 0.09;
        var z = 0;

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All WebGL shaders have finished loading!");

            for (var i = 0; i < 1; i++)
                BindModel( i );

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.pointProgram );

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
                if (key.up) z += 0.01;
                if (key.down) z -= 0.01;
                if (key.w) y += 0.01;
                if (key.s) y -= 0.01;

                var scale = 0.5;

                // Clear WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);

                gl.clear(gl.COLOR_BUFFER_BIT);
                gl.clear(gl.DEPTH_BUFFER_BIT);

                // Set "brick.png" as active texture to pass into the shader
                gl.activeTexture(gl.TEXTURE0);
                gl.bindTexture(gl.TEXTURE_2D, road.texture);
                gl.uniform1i(gl.getUniformLocation(Shader.pointProgram, 'image'), 0);

                // Indices of cube
                // var indices_cube = window.ref_arrayMDL[1][4];

                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.pointProgram, "Projection"), false, Projection.getAsFloat32Array());

                var View = new CanvasMatrix4();

                // Generate view matrix
                View.makeIdentity();
                View.translate(0, -0.25, -1);

                //car_angle += 0.05;

                // Set viewport to Upper Left corner
                gl.viewport(0, 0, width, height);

                // Default ambient color set to "white"
                rgb = [1.0, 1.0, 0.7];

                LightPosition = [x, y, z]; // some angle
                LightDirection = [0,-1,0]; // some other angle
                LightColor = [1, 1, 1]; // white-yellowish

                BindModel(1);
                Model.makeIdentity();
                Model.rotate(car_angle, 0, 1, 0);
                Model.translate(x,y,z);
                Model.scale(0.3, 0.3, 0.3);

                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "rgb"), rgb);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightPosition"), LightPosition);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightDirection"), LightDirection);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightColor"), LightColor);
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.pointProgram, "Model"), false, Model.getAsFloat32Array());
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.pointProgram, "View"), false, View.getAsFloat32Array());
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                BindModel(0);
                Model.makeIdentity();
                //Model.rotate(car_angle, 0, 1, 0);
                Model.translate(x, y, z );
                Model.scale(0.3, 0.3, 0.3);
                rgb = [1,1,1];
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "rgb"), rgb);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightPosition"), LightPosition);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightDirection"), LightDirection);
                gl.uniform3fv(gl.getUniformLocation(Shader.pointProgram, "LightColor"), LightColor);
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.pointProgram, "Model"), false, Model.getAsFloat32Array());
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.pointProgram, "View"), false, View.getAsFloat32Array());
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);
            });
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