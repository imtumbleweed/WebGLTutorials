precision mediump float;
attribute vec4 a_Position;
attribute vec4 a_Color;
attribute vec2 a_Texture;

varying vec4 color;
varying vec2 texture;

uniform mat4 Projection;
uniform mat4 Model;
uniform mat4 View;

uniform vec3 rgb;
uniform vec3 LightPosition;
uniform vec3 LightDirection;

void main()
{
    gl_Position = Projection * View * Model * a_Position;

    color = a_Color;

    texture = a_Texture;
}