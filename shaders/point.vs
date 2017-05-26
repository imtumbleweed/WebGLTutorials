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

    vec4 vert_Position = Model * a_Position;

    vec3 calc_LightDirection = normalize(LightPosition - vec3(vert_Position));

    float NdotL = max(dot(calc_LightDirection, normal), 0.0);

    vec4 ambient = vec4(0.3, 0.3, 0.3, 0.15);

    vec4 diffuse = vec4(0.7, 0.7, 0.7, 0.15);

    color = (ambient + diffuse) * a_Color * vec4(LightColor, 1) * NdotL;

    texture = a_Texture;
}