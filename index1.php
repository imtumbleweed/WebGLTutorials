<!doctype html>
<html>
<head>
    <title>Tutorial 1 - Drawing Arbitrary Points</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = 'http://www.tigrisgames.com/js/jquery.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/js/ui.js' type = 'text/javascript'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/shaders.js'></script>
    <script src = 'http://www.tigrisgames.com/fx/gl-1.js'></script>
    <script type = "text/javascript">

        /* -- Gl functions -- */ 

        var canvas = null;
        var gl = null;

        $(document).ready(function() {

            var canvas = document.getElementById('gl');
            
            var gl = GetWebGLContext(canvas);

            if (!gl) {
                console.log('Failed to set up WebGL.');

            } else {

                CreateShaderPrograms( gl );

                var T = setInterval(function() {

                    DrawPoint( gl ); // Draw a point in the middle of the canvas

                    for (var i = 0; i < 100; i++)
                        DrawPointUsingGlobalParameters( gl, -Math.random() + Math.random(), -Math.random() + Math.random(), 0.0 );

                }, 0);
            }

            /*
            $('#gl').on("mousemove", function( e ) { // Draw points at mouse click position

                gl.clearColor(0.0, 0.0, 0.0, 1.0);
                gl.clear(gl.COLOR_BUFFER_BIT);

                DrawPointAtMousePosition( canvas, gl, e );
            }); */

        });
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