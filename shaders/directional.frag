precision mediump float;

uniform sampler2D image;

varying vec4 color;
varying vec2 texture;

uniform mat4 Projection;
uniform mat4 Model;
uniform mat4 View;

uniform vec3 rgb;

void main() {

    gl_FragColor = color * vec4(rgb[0], rgb[1], rgb[2], 1) * texture2D(image, vec2(texture.s, texture.t));
}
