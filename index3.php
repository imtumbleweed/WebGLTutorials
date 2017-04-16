<!doctype html>
<html>
<head>
    <title>Tutorial 3 - Load shaders from script tags</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl-1.js'></script>
    <!-- Standard vertex shader //-->
    <script type = "glsl" id = "standard-vs">void main() {
            gl_Position = vec4(0.0, 0.0, 0.0, 1);
            gl_PointSize = 10.0;
        }
    </script>
    <!-- Fragment vertex shader //-->
    <script type = "glsl" id = "standard-frag">void main() {
            gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0);
        }
    </script>
    <script type = "text/javascript">

        /* -- Gl functions -- */

        var canvas = null;
        var gl = null;

        $(document).ready(function() {

            var canvas = document.getElementById('gl');

            gl = GetWebGLContext(canvas);

            if (!gl)
                console.log('Failed to set up WebGL.');

            else { // Load a shader from script tags

                var vs = document.getElementById("standard-vs").innerHTML;
                var fs = document.getElementById("standard-frag").innerHTML;

                Shader.standardProgram = InitializeShader(gl, vs, fs);

                gl.useProgram( Shader.standardProgram );
                gl.drawArrays(gl.POINTS, 0, 1);
            }
        });

        // An event that fires when all shader resources finish loading in CreateShadersFromFile
        window.webGLResourcesLoaded = function() {

            console.log("webGLResourcesLoaded(): All webGL shaders have finished loading!");

            // Start main drawing loop
            var T = setInterval(function() {

                if (!gl)
                    return;

                // Create WebGL canvas
                gl.clearColor(0.0, 0.0, 0.0, 1.0);
                gl.clear(gl.COLOR_BUFFER_BIT);

                DrawPoint();

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