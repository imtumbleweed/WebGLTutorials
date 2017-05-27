precision mediump float;

uniform sampler2D image;

varying vec4 color;
varying vec2 texture;

uniform mat4 Projection;
uniform mat4 Model;
uniform mat4 View;

uniform vec3 rgb;

uniform float column;
uniform float row;
uniform float sheet_size;
uniform float sprite_size;

void main() {

   // step is 0.0625f when sheet_size is 16.0
   float step = 1.0 / sheet_size;

   vec2 tex = vec2(step * column + texture.s * step,
                   step * row    + texture.t * step);

   gl_FragColor =
       vec4(rgb[0], rgb[1], rgb[2], 1) *
       texture2D(image, tex);
}
