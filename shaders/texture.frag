precision mediump float;

uniform sampler2D image;

varying vec4 color;
varying vec2 texture;

void main() {
    gl_FragColor = color;
}
