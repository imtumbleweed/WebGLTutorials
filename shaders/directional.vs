precision mediump float;
attribute vec4 a_Position;
attribute vec4 a_Color;
attribute vec2 a_Texture;
attribute vec4 a_Normal;

varying vec4 color;
varying vec2 texture;

uniform mat4 Projection;
uniform mat4 Model;
uniform mat4 View;

uniform vec3 rgb;
uniform vec3 LightPosition;
uniform vec3 LightDirection;
uniform vec3 LightColor;

void main()
{
    gl_Position = Projection * View * Model * a_Position;

    vec3 normal = normalize(vec3(a_Normal));

    float NdotL = max(dot(LightDirection, normal), 0.0);

    vec3 diffuse = vec3(0.5, 0.5, 0.5);

    color = a_Color * vec4(LightColor, 1) * NdotL;

    texture = a_Texture;
}