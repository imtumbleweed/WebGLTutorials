function makeCube() {

    return new Float32Array([
        // Side 1: facing the camera; z = 0.5 (side closest to camera)
                                //   z = -0.5 (farthest side away)
            -0.5, 0.5, 0.5,
            -0.5, -0.5, 0.5,
             0.5, -0.5, 0.5,

             -0.5, 0.5, 0.5,
             0.5, -0.5, 0.5,
             0.5, 0.5, 0.5

        ]
    );
}

function makeCubeColors() {

    return new Float32Array([
            // Side 1: facing the camera; z = 0.5 (side closest to camera)
            //   z = -0.5 (farthest side away)
            1.0, 0.0, 0.0,
            1.0, 0.0, 0.0,
            1.0, 1.0, 0.0,

            1.0, 0.0, 0.0,
            1.0, 1.0, 0.0,
            1.0, 0.0, 0.0
        ]
    );
}

function makeCubeTextures() {
    return new Float32Array([
            0.0, 0.0,
            0.0, 1.0,
            1.0, 1.0,

            0.0, 0.0,
            1.0, 1.0,
            1.0, 0.0,
        ]
    );
}