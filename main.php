<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Cyrus-Beck</title>
</head>
<body>
<canvas id='hw3' width='500px' height='500px' style="border: 1px solid #000;">
</canvas>
<script>
    let canvas = document.getElementById('hw3');
    let ctx = canvas.getContext('2d');
    let state = 0;
    let xp1_t, yp1_t;
    let xp1, yp1;
    let xp2, yp2;
    let xa, ya, xb, yb;
    let points_vertex_polygon = new Map();

    function line(x0, y0, x1, y1, color) {
        ctx.fillStyle = color;
        let dy = Math.abs(y1 - y0);
        let dx = Math.abs(x1 - x0);
        let d_max = Math.max(dx, dy);
        let d_min = Math.min(dx, dy);
        let x_dir = 1;
        if (x1 < x0)
            x_dir = -1;
        let y_dir = 1;
        if (y1 < y0)
            y_dir = -1;
        let eps = 0;
        let s = 1;
        let k = 2 * d_min;
        if (dy <= dx) {
            let y = y0;
            for (let x = x0; x * x_dir <= x1 * x_dir; x += x_dir) {
                ctx.fillRect(x * s, y * s, s, s);
                eps = eps + k;
                if (eps > d_max) {
                    y += y_dir;
                    eps = eps - 2 * d_max;
                }
            }
        } else {
            let x = x0;
            for (let y = y0; y * y_dir <= y1 * y_dir; y += y_dir) {
                ctx.fillRect(x * s, y * s, s, s);
                eps = eps + k;
                if (eps > d_max) {
                    x += x_dir;
                    eps = eps - 2 * d_max;
                }
            }
        }
    }

    canvas.addEventListener("click", function (val) {
        if (state === 0) {
            xp1_t = val.offsetX;
            yp1_t = val.offsetY;
            xp1 = val.offsetX;
            yp1 = val.offsetY;
            state = 1;
        } else if (state === 1) {
            xp2 = val.offsetX;
            yp2 = val.offsetY;
            line(xp1_t, yp1_t, xp2, yp2, "#000");
            points_vertex_polygon.set([xp1_t, yp1_t], [xp2, yp2]);
            xp1_t = val.offsetX;
            yp1_t = val.offsetY;
            state = 1;
        } else if (state === 2) {
            xa = val.offsetX;
            ya = val.offsetY;
            state = 3;
        } else if (state === 3) {
            xb = val.offsetX;
            yb = val.offsetY;
            let tmin = -1;
            let tmax = -1;
            let key_tmin;
            let key_tmax;
            line(xa, ya, xb, yb, "#fff");
            for (let key of points_vertex_polygon.keys()) {
                if (tmin === -1) {
                    tmin = ((ya - yb) * (key[0] - xa) + (xb - xa)
                        * (key[1] - ya)) / ((points_vertex_polygon.get(key)[0] - key[0])
                        * (yb - ya) + (points_vertex_polygon.get(key)[1] - key[1]) * (xa - xb));
                    key_tmin = key;
                    if (tmin > 1 || tmin < 0) {
                        tmin = -1;
                    } else {
                        continue;
                    }

                }

                if (tmax === -1) {
                    tmax = ((ya - yb) * (key[0] - xa) + (xb - xa)
                        * (key[1] - ya)) / ((points_vertex_polygon.get(key)[0] - key[0])
                        * (yb - ya) + (points_vertex_polygon.get(key)[1] - key[1]) * (xa - xb));
                    key_tmax = key;
                    if (tmax > 1 || tmax < 0) {
                        tmax = -1;
                        continue;
                    }
                }
                if (tmin <= 1 && tmin >= 0 && tmax <= 1 && tmax >= 0) {
                    let x0a = (points_vertex_polygon.get(key_tmin)[0] - key_tmin[0]) * tmin + key_tmin[0];
                    let y0a = (points_vertex_polygon.get(key_tmin)[1] - key_tmin[1]) * tmin + key_tmin[1];
                    let x0b = (points_vertex_polygon.get(key_tmax)[0] - key_tmax[0]) * tmax + key_tmax[0];
                    let y0b = (points_vertex_polygon.get(key_tmax)[1] - key_tmax[1]) * tmax + key_tmax[1];

                    line(x0a, y0a, x0b, y0b, "#fff200");
                    line(points_vertex_polygon.get(key_tmin)[0],
                        points_vertex_polygon.get(key_tmin)[1], key_tmin[0], key_tmin[1], "#000");
                    break;
                }
            }
            state = 2;

        }


    });

    canvas.addEventListener('contextmenu', function () {
        if (state === 1) {
            line(xp1_t, yp1_t, xp1, yp1, " #000");
            points_vertex_polygon.set([xp1_t, yp1_t], [xp1, yp1]);
            state = 2;
        }
    });


</script>
</body>
</html>