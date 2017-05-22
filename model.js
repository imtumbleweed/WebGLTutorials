window.ModelsLoaded = false;

window.ref_arrayMDL = null;

function LoadModels() {

    // Load model into "window.ref_arrayMDL"
    LoadPLY("racingtrack2.ply");

    // When HTTP requests in LoadPLY are finished,
    // Model data (vertices, textures, normals, etc.) will be stored in:
    //
    //     window.ref_arrayMDL

}