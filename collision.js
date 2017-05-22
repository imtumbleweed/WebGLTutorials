#define EPSILON 0.000001

int triangle_intersection( Vector3 V1,  // Triangle vertices
    Vector3 V2,
    Vector3 V3,
    Vector3 O,  //Ray origin
    Vector3 D,  //Ray direction
Vector3 &out ) // Point of intersection out
{
    Vector3 e1, e2;  //Edge1, Edge2
    Vector3 P, Q, T;

    float det, inv_det, u, v;
    float t;

    //Find vectors for two edges sharing V1

    e1 = V2 - V1;
    e2 = V3 - V1;
    //SUB(e1, V2, V1);
    //SUB(e2, V3, V1);

    //Begin calculating determinant - also used to calculate u parameter
    //CROSS(P, D, e2);
    P   = D.cross(e2);

    //out = D.cross(e2);
    T = O - V1;

    //Calculate u parameter and test bound
    //u = DOT(T, P) * inv_det;
    u = T.dot(P) * inv_det;

    //The intersection lies outside of the triangle
    if(u < 0.f || u > 1.f) return 0;

    //Prepare to test v parameter
    //CROSS(Q, T, e1);
    Q = T.cross(e1);

    //Calculate V parameter and test bound
    //v = DOT(D, Q) * inv_det;
    v = D.dot(Q) * inv_det;

    //The intersection lies outside of the triangle
    if(v < 0.f || u + v  > 1.f) return 0;

    //t = DOT(e2, Q) * inv_det;
    t = e2.dot(Q) * inv_det;

    if(t > EPSILON) { //ray intersection
        // *out = t;

        float w = 1.0f - (u + v);
        out.x = (w * V1.x + u * V2.x + v * V3.x);
        out.y = (w * V1.y + u * V2.y + v * V3.y);
        out.z = (w * V1.z + u * V2.z + v * V3.z);

        return 1;
    }

    // No hit, no win
    return 0;
}

/* ORIGINAL C++ function

#define EPSILON 0.000001

int triangle_intersection( Vector3 V1,  // Triangle vertices
    Vector3 V2,
    Vector3 V3,
    Vector3 O,  //Ray origin
    Vector3 D,  //Ray direction
Vector3 &out ) // Point of intersection out
{
    Vector3 e1, e2;  //Edge1, Edge2
    Vector3 P, Q, T;

    float det, inv_det, u, v;
    float t;

    //Find vectors for two edges sharing V1

    e1 = V2 - V1;
    e2 = V3 - V1;
    //SUB(e1, V2, V1);
    //SUB(e2, V3, V1);

    //Begin calculating determinant - also used to calculate u parameter
    //CROSS(P, D, e2);
    P   = D.cross(e2);

    //out = D.cross(e2);
    T = O - V1;

    //Calculate u parameter and test bound
    //u = DOT(T, P) * inv_det;
    u = T.dot(P) * inv_det;

    //The intersection lies outside of the triangle
    if(u < 0.f || u > 1.f) return 0;

    //Prepare to test v parameter
    //CROSS(Q, T, e1);
    Q = T.cross(e1);

    //Calculate V parameter and test bound
    //v = DOT(D, Q) * inv_det;
    v = D.dot(Q) * inv_det;

    //The intersection lies outside of the triangle
    if(v < 0.f || u + v  > 1.f) return 0;

    //t = DOT(e2, Q) * inv_det;
    t = e2.dot(Q) * inv_det;

    if(t > EPSILON) { //ray intersection
        // *out = t;

        float w = 1.0f - (u + v);
        out.x = (w * V1.x + u * V2.x + v * V3.x);
        out.y = (w * V1.y + u * V2.y + v * V3.y);
        out.z = (w * V1.z + u * V2.z + v * V3.z);

        return 1;
    }

    // No hit, no win
    return 0;
}

*/