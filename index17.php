<!doctype html>
<html>
<head>
    <title>Tutorial 17 - Kart Racing Example</title>
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
    <script src = 'http://www.tigrisgames.com/fx/model3.js?v=4'></script>
    <script src = 'http://www.tigrisgames.com/fx/keyboard.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/mouse.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/segment.js'></script>
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

        var x = -2.4149999999999707;
        var y = 0.45501865507475714;
        var z = -10.55500000000025;

        function Automobile()
        {
            this.x = -2.4149999999999707;
            this.y = 0.45501865507475714;
            this.z = -10.55500000000025;

            this.velocity = 0;

            this.angle = 0;

            this.angle_velocity = 0;
        }

        var Kart = new Automobile();

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All WebGL shaders have finished loading!");

            for (var i = 0; i <= 1; i++)
                BindModel( i );

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.directionalProgram );

            // Create storage for our matrices
            var Projection = new CanvasMatrix4();
            var ModelView = new CanvasMatrix4();
            var ObserverView = new CanvasMatrix4();

            var Model = new CanvasMatrix4();
            var View = new CanvasMatrix4();

            var model_angle = 0;

            // Size of our canvas
            var width = 800;
            var height = 600;

            $("#gl").css( { "width" : width + "px", "height" : height + "px" } );

            // Start main drawing loop
            var T = setInterval(function() {

                if (!gl)
                    return;

                if (key.left) Kart.angle_velocity -= 0.005;
                if (key.right) Kart.angle_velocity += 0.005;
                if (key.up) Kart.velocity += 0.0001;
                if (key.down) Kart.velocity -= 0.0001;

                // Convert Kart's angle to vector coordinates
                var dir_xz = ang2vec(Kart.angle);
                var dir_x = dir_xz[0]; // on x-axis
                var dir_z = dir_xz[1]; // on z-axis

                // Move Kart in direction of its angle
                Kart.x += dir_x * Kart.velocity;
                Kart.z += dir_z * Kart.velocity;

                // Smoothly rotate the kart based on angle velocity
                Kart.angle += Kart.angle_velocity;

                // Friction
                Kart.angle_velocity -= (Kart.angle_velocity * 0.0075);
                Kart.velocity -= (Kart.velocity * 0.005);

                // Clear WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);

                gl.clear(gl.COLOR_BUFFER_BIT);
                gl.clear(gl.DEPTH_BUFFER_BIT);

                // Set "brick.png" as active texture to pass into the shader
                gl.activeTexture(gl.TEXTURE0);
                gl.bindTexture(gl.TEXTURE_2D, road.texture);
                gl.uniform1i(gl.getUniformLocation(Shader.directionalProgram, 'image'), 0);

                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.directionalProgram, "Projection"), false, Projection.getAsFloat32Array());

                var View = new CanvasMatrix4();

                // Generate view matrix
                View.makeIdentity();

                View.lookat2(
                    Kart.x-1*dir_x, Kart.y+0.25, Kart.z-1*dir_z,
                    Kart.x, Kart.y+0.25, Kart.z,

                0,1,0);

                // Set viewport to Upper Left corner
                gl.viewport(0, 0, width, height);

                // Default ambient color set to "white"
                rgb = [1.0, 1.0, 0.7];

                LightPosition = [5, 3, -10]; // some angle
                LightDirection = [-0.25, 1.75, 3]; // some other angle
                LightColor = [1, 1, 0.9]; // white-yellowish

                BindModel(1);
                Model.makeIdentity();
                Model.rotate(car_angle, 0, 1, 0);
                Model.translate(0, 0, 0);

                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "rgb"), rgb);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightPosition"), LightPosition);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightDirection"), LightDirection);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightColor"), LightColor);
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.directionalProgram, "Model"), false, Model.getAsFloat32Array());
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.directionalProgram, "View"), false, View.getAsFloat32Array());
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                // Draw Kart
                BindModel(0);
                Model.makeIdentity();
                Model.translate(Kart.x, Kart.y, Kart.z);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "rgb"), rgb);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightPosition"), LightPosition);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightDirection"), LightDirection);
                gl.uniform3fv(gl.getUniformLocation(Shader.directionalProgram, "LightColor"), LightColor);
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.directionalProgram, "Model"), false, Model.getAsFloat32Array());
                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.directionalProgram, "View"), false, View.getAsFloat32Array());
                gl.drawElements(gl.TRIANGLES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                var vertices = window.ref_arrayMDL[1][0];

                // At the sphere's center
                var downRayPosition = new Vector(Kart.x, Kart.y+2, Kart.z);

                // cast down on Y axis
                var downRayDirection = new Vector(0, -5, 0);

                for (var v = 0; v < vertices.length; v += 9)
                {
                    var v3 = new Vector(vertices[v + 0], vertices[v + 1], vertices[v + 2]);
                    var v2 = new Vector(vertices[v + 3], vertices[v + 4], vertices[v + 5]);
                    var v1 = new Vector(vertices[v + 6], vertices[v + 7], vertices[v + 8]);

                    var intersect = triangle_intersection(v1, v2, v3, downRayPosition, downRayDirection);

                    // There was an intersection!
                    if (intersect != 0) {

                        var inx = intersect[0];
                        var iny = intersect[1];
                        var inz = intersect[2];

                        // Adjust the sphere model's Y position to match intersection on the Y axis
                        // But also add sphere's radius 0.05 to make it appear collide with the terrain
                        Kart.y = iny + 0.025;

                        //console.log("iny="+iny);

                        window.collision = true;
                        break; // end the loop
                    } else {  window.collision = false; }
                }

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