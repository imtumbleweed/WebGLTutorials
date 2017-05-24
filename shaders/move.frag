precision mediump float;

uniform sampler2D image;

varying vec4 color;
varying vec2 texture;

uniform mat4 Projection;
uniform mat4 Model;
uniform mat4 View;

void main() {

    gl_FragColor = texture2D(image, vec2(texture.s, texture.t));
}
