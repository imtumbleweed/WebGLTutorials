var MAX_MODELS = 16;

window.ModelsLoaded = false;

window.ref_arrayMDL = new Array();

window.current_Model_ID = 0;

function ModelData() {
    this.vertexbuffer = null; //gl.createBuffer();
    this.colorbuffer = null; //gl.createBuffer();
    this.texturebuffer = null; //gl.createBuffer();
    this.normalbuffer = null; //gl.createBuffer();
    this.indexbuffer = null; //gl.createBuffer();
}

var Model = new Array();

for (var i = 0; i < MAX_MODELS; i++)
    Model[i] = new ModelData();

function LoadModels()
{
    LoadPLY("stairs.ply");
    LoadPLY("sphere.ply");
    // LoadPLY("racingtrack2.ply");
}

// Bind model for rasterization;
// Assumes model vertex data is fully loaded
function BindModel(model_ID)
{
    var i = model_ID;

    var vertices = window.ref_arrayMDL[i][0]; // Get vertex data from loaded model
    var colors = window.ref_arrayMDL[i][1];
    var uvs = window.ref_arrayMDL[i][2];
    var normals = window.ref_arrayMDL[i][3];
    var indices = window.ref_arrayMDL[i][4];

    // set global pointer to this model's indices
    model_indices = window.ref_arrayMDL[i][4];

    if (Model[i].vertexbuffer == null) Model[i].vertexbuffer = gl.createBuffer();
    if (Model[i].colorbuffer == null) Model[i].colorbuffer = gl.createBuffer();
    if (Model[i].texturebuffer == null) Model[i].texturebuffer = gl.createBuffer();
    if (Model[i].normalbuffer == null) Model[i].normalbuffer = gl.createBuffer();
    if (Model[i].indexbuffer == null) Model[i].indexbuffer = gl.createBuffer();

    var BYTESIZE = vertices.BYTES_PER_ELEMENT;

    // Bind vertex buffer to ARRAY_BUFFER
    gl.bindBuffer(gl.ARRAY_BUFFER, Model[i].vertexbuffer);
    // Send our vertex data to the buffer using floating point array
    gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);
    var coords = gl.getAttribLocation(Shader.directionalProgram, "a_Position");
    gl.vertexAttribPointer(coords, 3, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(coords); // Enable it
    // We're done; now we have to unbind the buffer
    gl.bindBuffer(gl.ARRAY_BUFFER, null);

    // Bind colorbuffer to ARRAY_BUFFER
    gl.bindBuffer(gl.ARRAY_BUFFER, Model[i].colorbuffer);
    // Send our vertex data to the buffer using floating point array
    gl.bufferData(gl.ARRAY_BUFFER, colors, gl.STATIC_DRAW);
    var col = gl.getAttribLocation(Shader.directionalProgram, "a_Color");
    gl.vertexAttribPointer(col, 3, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(col); // Enable it
    // We're done; now we have to unbind the buffer
    gl.bindBuffer(gl.ARRAY_BUFFER, null);

    // Bind texturebuffer to ARRAY_BUFFER
    gl.bindBuffer(gl.ARRAY_BUFFER, Model[i].texturebuffer);
    // Send our texture image data to the buffer using floating point array
    gl.bufferData(gl.ARRAY_BUFFER, uvs, gl.STATIC_DRAW);
    var uv = gl.getAttribLocation(Shader.directionalProgram, "a_Texture");
    gl.vertexAttribPointer(uv, 2, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(uv); // Enable it
    // We're done; now we have to unbind the buffer (optional but probably a good idea)
    gl.bindBuffer(gl.ARRAY_BUFFER, null);

    // Bind normalbuffer to ARRAY_BUFFER
    gl.bindBuffer(gl.ARRAY_BUFFER, Model[i].normalbuffer);
    // Send our vertex data to the buffer using floating point array
    gl.bufferData(gl.ARRAY_BUFFER, normals, gl.STATIC_DRAW);
    var n = gl.getAttribLocation(Shader.directionalProgram, "a_Normal");
    gl.vertexAttribPointer(n, 3, gl.FLOAT, false, 0, 0);
    gl.enableVertexAttribArray(n); // Enable it
    // We're done; now we have to unbind the buffer
    gl.bindBuffer(gl.ARRAY_BUFFER, null);

    // Bind indices to ELEMENT_ARRAY_BUFFER
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, Model[i].indexbuffer);
    // Send index (indices) data to this buffer
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(indices), gl.STATIC_DRAW);
}