<!doctype html>
<html>
<head>
    <title>Tutorial 12 - 3D Camera</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js?v=5'></script>
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
            gl.useProgram( Shader.textureMapProgram );

            // Create storage for our matrices
            var Projection = new CanvasMatrix4();
            var ModelView = new CanvasMatrix4();
            var ObserverView = new CanvasMatrix4();

            var model_angle = -150;

            // Size of our canvas
            var width = 800;
            var height = 600;

            $("#gl").css({"width":width+"px","height":height/2+"px"});

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
                gl.uniform1i(gl.getUniformLocation(Shader.textureMapProgram, 'image'), 0);

                // Indices of cube
               // var indices_cube = window.ref_arrayMDL[1][4];

                // Set viewport for displaying camera
                gl.viewport(0, 0, width/2, height);

                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.textureMapProgram, "Projection"), false, Projection.getAsFloat32Array());

                // Generate model-view matrix
                ObserverView.makeIdentity();
                ObserverView.scale(scale, scale, scale);
                ObserverView.lookat2(x, y, z,  // target
                    1, 1, 0,  // camera location
                    0, 1, 0); // up-vector

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.textureMapProgram, "ModelView"), false, ObserverView.getAsFloat32Array());

                // Draw racing track
                BindModel(1);
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                // Draw cube
                BindModel(0);
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);




                // Now draw the same scene again from the camera's point of view on our second viewport
                gl.viewport(width/2, 0, width/2, height);

                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);

                // same projection -- for now
                //gl.uniformMatrix4fv(gl.getUniformLocation(Shader.textureMapProgram, "Projection"), false, Projection.getAsFloat32Array());

                // Generate Observer's model-view matrix
                ModelView.makeIdentity();
                ModelView.scale(scale, scale, scale);
                ModelView.rotate(model_angle, 0, 1, 0);
                ModelView.translate(x-2, y-1.9, z);

                // Pass ObserverView to the shader
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.textureMapProgram, "ModelView"), false, ModelView.getAsFloat32Array());

                // Draw triangle
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                // Draw racing track
                BindModel(1);
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                // Draw cube
                BindModel(0);
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