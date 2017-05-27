<!doctype html>
<html>
<head>
    <title>Tutorial 22 - Line Segment Intersection</title>
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
    <script src = 'http://www.tigrisgames.com/fx/model6.js?v=1'></script>
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

        var x = 0;
        var y = 0.09;
        var z = 0;

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All WebGL shaders have finished loading!");

            for (var i = 0; i < 1; i++)
                BindModel( i );

            // Use our standard shader program for rendering this triangle
            gl.useProgram( Shader.vertexColorProgram );

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

            function i2xy(index, mapWidth)
            {
                var x = index % mapWidth;
                var y = Math.floor(index/mapWidth);
                return [x, y];
            }

            function xy2i(x, y, mapWidth)
            {
                return y * mapWidth + x;
            }

            var flame_index = 0;

            InitializeMouse();

            window.Mouse.Initialize("#gl");

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
                gl.bindTexture(gl.TEXTURE_2D, fire.texture);
                gl.uniform1i(gl.getUniformLocation(Shader.vertexColorProgram, 'image'), 0);

                // Indices of cube
                // var indices_cube = window.ref_arrayMDL[1][4];

                // Create camera perspective matrix
                Projection.makeIdentity();
                Projection.perspective(45, width / height, 0.05, 1000);
                //Projection.ortho(0, 0, 100, 100, -100, 100);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.vertexColorProgram, "Projection"), false, Projection.getAsFloat32Array());

                var View = new CanvasMatrix4();

                View.makeIdentity();
                View.translate(0, 0, -10);

                // Set viewport to Upper Left corner
                gl.viewport(0, 0, width, height);

                // Default ambient color set to "white"
                rgb = [1.0, 1.0, 0.7];

                LightPosition = [x, y, z]; // some angle
                LightDirection = [0,-1,0]; // some other angle
                LightColor = [1, 1, 1]; // white-yellowish

                console.log(Mouse.x);

                // Define our line segments
                var segmentA = new Segment(-0.5*10, -0.5*10,  1.0*10, 1.0*10 + (Mouse.x * 0.01)*10);
                var segmentB = new Segment( 0.5*10, -0.75*10, -1.0*10, 1.0*10);

                var ix = 0;
                var iy = y;

                // Get intersection point (if any)
                if (segmentA.intersect(segmentB) == DO_INTERSECT)
                {
                    ix = window.int_x/1000;
                    iy = window.int_y/1000;
                }
                else
                {
                    // Segments do not intersect
                }

                var dir = ang2vec(player_angle);
                var dirx = dir.x;
                var diry = dir.y;

                // Bind sphere model
                BindModel(0);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.vertexColorProgram, "View"), false, View.getAsFloat32Array());

                Model.makeIdentity();
                Model.translate(ix, iy, 0);
                Model.scale(0.1, 0.1, 0.1);

                gl.uniformMatrix4fv(gl.getUniformLocation(Shader.vertexColorProgram, "Model"), false, Model.getAsFloat32Array());
                gl.uniform3fv(gl.getUniformLocation(Shader.vertexColorProgram, "rgb"), rgb);
                gl.drawElements(gl.LINES, model_indices.length, gl.UNSIGNED_SHORT, 0);

                // Draw line segments

                // Specify triangle vertex data:
                var vertices = new Float32Array([
                    segmentA.x, segmentA.y, 0,
                    segmentA.x + segmentA.vecx, segmentA.y + segmentA.vecy, 0,
                    segmentB.x, segmentB.y, 0,
                    segmentB.x + segmentB.vecx, segmentB.y + segmentB.vecy, 0
                ]);

                var colors = new Float32Array([

                    1.0, 0.0, 0.0,
                    1.0, 1.0, 0.0,
                    0.0, 0.0, 1.0,
                    0.0, 1.0, 1.0,
                ]);

                var indices = [0, 1, 2, 3];

                // Create buffer objects for storing triangle vertex and index data
                var vertexbuffer = gl.createBuffer();
                var colorbuffer = gl.createBuffer();
                var indexbuffer = gl.createBuffer();

                var BYTESIZE = vertices.BYTES_PER_ELEMENT;

                // Bind and create enough room for our data on respective buffers

                // Bind it to ARRAY_BUFFER
                gl.bindBuffer(gl.ARRAY_BUFFER, vertexbuffer);
                // Send our vertex data to the buffer using floating point array
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
                var coords = gl.getAttribLocation(Shader.vertexColorProgram, "a_Position");
                gl.vertexAttribPointer(coords, 3, gl.FLOAT, false, 0, 0);
                gl.enableVertexAttribArray(coords); // Enable it
                // We're done; now we have to unbind the buffer
                gl.bindBuffer(gl.ARRAY_BUFFER, null);

                // Bind it to ARRAY_BUFFER
                gl.bindBuffer(gl.ARRAY_BUFFER, colorbuffer);
                // Send our vertex data to the buffer using floating point array
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(colors), gl.STATIC_DRAW);
                var colors = gl.getAttribLocation(Shader.vertexColorProgram, "a_Color");
                gl.vertexAttribPointer(colors, 3, gl.FLOAT, false, 0, 0);
                gl.enableVertexAttribArray(colors); // Enable it
                // We're done; now we have to unbind the buffer
                gl.bindBuffer(gl.ARRAY_BUFFER, null);

                // Bind it to ELEMENT_ARRAY_BUFFER
                gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexbuffer);
                // Send index (indices) data to this buffer
                gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);

                // Use our standard shader program for rendering this triangle
                gl.useProgram( Shader.vertexColorProgram );

                // Draw triangle
                gl.drawElements(gl.LINES, indices.length, gl.UNSIGNED_SHORT, 0);

            }, 24);
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