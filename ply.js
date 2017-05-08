// Path to the folder where models are stored
var ModelFolderPath = "http://localhost/tigrisgames.com/fx/model/";

var PLY_Vertices = 0;

var PLY_Faces = 0;

var ReadingPLYData = false; // For skipping header

var PLY_DataLenght = 12;    // 12 entries per vertex (x,y,z,nx,ny,nz,r,g,b,u,v)

// PLY file vertex entry format
function PLY_Vertex(x,y,z,nx,ny,nz,u,v,r,g,b) {
    this.x = 0; // a_Position
    this.y = 0;
    this.z = 0;
    this.nx= 0; // a_Normal
    this.ny= 0;
    this.nz= 0;
    this.r = 0; // a_Color
    this.g = 0;
    this.b = 0;
    this.u = 0; // a_Texture
    this.v = 0;
}

function LoadPLY(filename)
{
    var xmlhttp = new XMLHttpRequest();

    // Execute first Ajax call to load the vertex shader
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
                var data = xmlhttp.responseText;
                var lines = data.split("\n");

                // Output some data about the model
                console.log("PLY number of lines = " + lines.length);

                var PLY_index = 0;

                // Read each line of the file and pack it into the model
                for (var i = 0; i < lines.length; i++)  {

                    // Model data starts only after "end_header"
                    if (lines[i] == "end_header") ReadingPLYData = true;

                    if (ReadingPLYData)
                    {
                        // Allocate enough space for vertices
                        var vertices = new Array(PLY_Vertices);

                        // Allocate enough space for faces
                        var faces = new Array(PLY_Faces);

                        // Read actual vertices...
                        for (var i = 0; i < PLY_Vertices; i++)
                        {
                            // Read vertex data from PLY file; one line at a time
                            var v = lines[i].split(" ");

                            // Read each entry
                            vertices[PLY_index + 0] = v[0];

                            PLY_index += PLY_DataLenght;
                        }

                    } else { // Still reading header...

                        // Read number of vertices stored in the file
                        if (lines[i].substr(0, "element vertex".length) == "element vertex")
                            PLY_Vertices = lines[i].split(" ")[2];

                        // Read number of faces stored in the file
                        if (lines[i].substr(0, "element face".length) == "element face")
                            PLY_Faces = lines[i].split(" ")[2];
                    }
                }

                console.log("PLY_Vertices = " + PLY_Vertices);
                console.log("PLY_Faces = " + PLY_Faces);
            }
        }
    };

    console.log("Loading Model <" + filename + ">...");

    xmlhttp.open("GET", ModelFolderPath + filename, true);
    xmlhttp.send();

    var vertices = new Float32Array([]);
    var colors = new Float32Array([]);
    var uvs = new Float32Array([]);
}