const cache = {
    15: ["#cc2929", "#e66717", "#ffc600", "#9eb336", "#71cc29", "#22e617", "#00ff55", "#36b390", "#29bacc", "#177ee6", "#001cff", "#5836b3", "#9629cc", "#e617da", "#ff008e"]
};

function hexColors(t) {
    t = parseInt(t);
    // distribute the colors evenly on
    // the hue range (the 'H' in HSV)
    var i = 350 / t;

    // hold the generated colors
    var r = [];
    var sv = 70;
    for (var x = 0; x < t; x++) {
        // alternate the s, v for more
        // contrast between the colors.
        sv = sv > 90 ? 70 : sv+10;
        var rgb = hsvToRgb(i * x, sv, sv);
        r.push(rgbToHex(rgb[0],rgb[1],rgb[2]));
    }
    return r;
}

/**
 * HSV to RGB color conversion
 *
 * H runs from 0 to 360 degrees
 * S and V run from 0 to 100
 *
 * Ported from the excellent java algorithm by Eugene Vishnevsky at:
 * http://www.cs.rit.edu/~ncs/color/t_convert.html
 */
function hsvToRgb(h, s, v) {
    var r, g, b;
    var i;
    var f, p, q, t;

    // Make sure our arguments stay in-range
    h = Math.max(0, Math.min(360, h));
    s = Math.max(0, Math.min(100, s));
    v = Math.max(0, Math.min(100, v));

    // We accept saturation and value arguments from 0 to 100 because that's
    // how Photoshop represents those values. Internally, however, the
    // saturation and value are calculated from a range of 0 to 1. We make
    // That conversion here.
    s /= 100;
    v /= 100;

    if (s == 0) {
        // Achromatic (grey)
        r = g = b = v;
        return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
    }

    h /= 60; // sector 0 to 5
    i = Math.floor(h);
    f = h - i; // factorial part of h
    p = v * (1 - s);
    q = v * (1 - s * f);
    t = v * (1 - s * (1 - f));

    switch (i) {
        case 0:
            r = v;
            g = t;
            b = p;
            break;

        case 1:
            r = q;
            g = v;
            b = p;
            break;

        case 2:
            r = p;
            g = v;
            b = t;
            break;

        case 3:
            r = p;
            g = q;
            b = v;
            break;

        case 4:
            r = t;
            g = p;
            b = v;
            break;

        default: // case 5:
            r = v;
            g = p;
            b = q;
    }

    return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}
function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}
function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

export default {
    getCache() {
        return cache;
    },
    /**
     *
     * @param {number} index - -1 returns an array of all max created colors
     * @param {number} max
     * @returns {*}
     */
    getColor(index, max) {
        if (!cache.hasOwnProperty(max)) {
            cache[max] = hexColors(max);
            // console.info(`generated ${max} colors`, cache[max]);
        }
        if (index === -1)
            return cache[max];
        return cache[max][index];
    },
    hexColors,
    hsvToRgb,
    rgbToHex,
    componentToHex
}