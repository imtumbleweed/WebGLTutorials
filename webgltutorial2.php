<!doctype html>
<html>
<head>
    <title>Tutorial 3 - Load shaders from script tags</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src = '../js/jquery.js' type = 'text/javascript'></script>
    <script src = '../js/ui.js' type = 'text/javascript'></script>
    <script src = '../fx/gl.js'></script>
    <script src = '../fx/shader.js'></script>
    <!-- Standard vertex shader //-->
    <script type = "glsl" id = "standard-vs">void main() {
        gl_Position = vec4(0.0, 0.0, 0.0, 1); // x,y and z
        gl_PointSize = 10.0;
    }
    </script>
    <!-- Standard fragment shader //-->
    <script type = "glsl" id = "standard-fs">void main() {
        gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0); // r,g and b
    }
    </script>
    <script type = "text/javascript">

        /* -- Gl functions -- */

        var canvas = null;

        $(document).ready(function() {

            var gl = InitializeWebGL();

            if (!gl)
                console.log('Failed to set up WebGL.');

            else { // Load a shader from script tags

                var vs = document.getElementById("standard-vs").innerHTML;
                var fs = document.getElementById("standard-fs").innerHTML;

                var standardProgram = InitializeShader(gl, vs, fs);

                gl.useProgram(standardProgram);

                gl.drawArrays(gl.POINT, 0, 1);

            }
        });

    </script>
</head>
<style>
    #fx { position: relative; margin: 0 auto; width: 1000px; height: 500px; border: 1px solid gray; }
    #gl { width: 800px; height: 600px; }
</style>
<body style = "background: #505050; padding: 32px;">
<canvas id = "gl" width = "800" height = "600"></canvas>
</body>
</html>