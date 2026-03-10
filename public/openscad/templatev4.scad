/* [Text] */
// Text
text = "TRi";

/* [General Settings] */
// Font
font = "Anton"; // [Archivo Black, Bangers, Bungee, Changa One, Bebas Neue, Poppins Black]

// Font size
font_size = 18; // [5:1:50]

// Global spacing multiplier
spacing = 0.65; // [0.1:0.05:2]

// Use automatic spacing based on character width
use_auto_spacing = true; // [true,false]

/* [Colors] */
// Use single color
use_single_color = true; // [true,false]


keychainHoleSize = 4.5; // Diameter of the keychain hole
keychainHoleOffset = -2;// Offset of the keychain hole from the left
// Radius of edge, Increase to fill in gaps
r = 2;

// Box customization parameters
boxWidth = 0;      // Width of the box
boxHeight = 0;     // Height of the box
boxXOffset = 0;     // Horizontal offset for the box
boxYOffset = -30;   // Vertical offset for the box
// Color array definition
$fn = 100;
thickness = 4;
available_colors = [
    ["Red", [1, 0, 0]],
    ["Dark Red", [0.5, 0, 0]],
    ["Green", [0, 1, 0]],
    ["Dark Green", [0, 0.5, 0]],
    ["Blue", [0, 0, 1]],
    ["Dark Blue", [0, 0, 0.5]],
    ["Yellow", [1, 1, 0]],
    ["Orange", [1, 0.5, 0]],
    ["Purple", [0.5, 0, 0.5]],
    ["Pink", [1, 0.4, 0.7]],
    ["White", [1, 1, 1]],
    ["Black", [0, 0, 0]],
    ["Light Gray", [0.8, 0.8, 0.8]],
    ["Dark Gray", [0.3, 0.3, 0.3]],
    ["Turquoise", [0, 0.8, 0.8]]
];

// General color
general_color = "Green";

// Ring color
ring_color = "Green";

/* [Individual Adjustments] */
position_1  = 0;
position_2  = 0;
position_3  = 0;
position_4  = 0;
position_5  = 0;
position_6  = 0;
position_7  = 0;
position_8  = 0;
position_9  = 0;
position_10 = 0;
position_11 = 0;
position_12 = 0;

/* [Heights] */
// Height for odd letters
odd_height = 11.5; // [1:0.5:10]

// Height for even letters
even_height = 8; // [1:0.5:10]

/* [Ring] */
show_ring = true;
ring_x = -4.0;
ring_y = 0.5;
ring_height = 3;
ring_size = 8;

/* [Lowercase i Connector] */
enable_i_connector = true; // [true,false]
i_connector_width  = 4.3;  // [0.4:0.1:2]
i_connector_depth  = 2.6;  // [0.4:0.1:2]

/* ========================================================= */
/* ===================== FUNCTIONS ========================= */
/* ========================================================= */

function get_color_rgb(color_name) =
    let(c = [for(x = available_colors) if (x[0] == color_name) x[1]])
    len(c) > 0 ? c[0] : [1,1,1];

function get_height(pos) =
    (pos % 2 == 0) ? odd_height : even_height;

function get_adjustment(pos) =
    pos == 0  ? position_1  :
    pos == 1  ? position_2  :
    pos == 2  ? position_3  :
    pos == 3  ? position_4  :
    pos == 4  ? position_5  :
    pos == 5  ? position_6  :
    pos == 6  ? position_7  :
    pos == 7  ? position_8  :
    pos == 8  ? position_9  :
    pos == 9  ? position_10 :
    pos == 10 ? position_11 :
    pos == 11 ? position_12 : 0;
    
function get_cumulative_adjustment(pos) =
    pos < 0 ? 0 :
    get_cumulative_adjustment(pos - 1) + get_adjustment(pos);
// Character width heuristic
function in_string(c, s) = len(search(c, s)) > 0;

function get_letter_width(c) =
    in_string(c, 
    "I" ) ? 0.3
    : in_string(c, 
    "ilI!j|[]'.," ) ? 0.36
    : in_string(c, 
    "ft") ? 0.45
    : in_string(c, 
    "r1") ? 0.52
    : in_string(c,
    "0123456789TFE") ? 0.6
    : in_string(c,
    "zZLy") ? 0.7
    : in_string(c,
    "vxSJY") ? 0.8
    : in_string(c, 
    "w") ? 1.15
    : in_string(c, 
    "WmM@#") ? 1.35
    : in_string(c, 
    "@") ? 1.5
    : 0.87;

function get_auto_spacing(pos) =
    pos < 0 ? 0 :
    get_auto_spacing(pos - 1)
    + (font_size * spacing * get_letter_width(text[pos]));

function is_lowercase_i(c) = c == "i" || c == "j";

kerning_pairs = [
    ["AV", -0.1],
    ["VA", -0.12],
    ["AW", -0.1],
    ["WA", -0.1],
    ["AT", -0.06],
    ["TA", -0.05],
    ["AY", -0.1],
    ["YA", -0.1],
    ["PA", -0.09],
    ["TT", 0.1],
    
    ["wa", 0.1],
    ["ya", -0.06],
    ["tt", 0.1],
    ["wt", 0.1],
    ["wt", 0.1],
    ["wv", 0.06],
    ["yw", -0.06],
    ["yv", -0.06],
    ["aw", -0.06],
    ["ij", -0.05],
    ["Ij", -0.05],
    ["je", -0.1],
    ["ji", -0.15],
    ["aT", -0.05],
    ["Ta", -0.05],
    ["At", -0.05],
    ["tA", -0.05],
    ["at", -0.05],
    ["ta", -0.05],
    ["ox", -0.05],
    ["xo", -0.05],
    ["Tv", -0.05],
    ["vT", -0.05],
    ["rJ", -0.13],
    ["Yx", -0.1],
    ["xY", -0.1],
    ["Yr", -0.1],
    ["YJ", -0.2],
    ["Yy", -0.1],
    ["Tx", -0.05],
    ["xT", -0.05],
    ["Rj", -0.05],
    ["Xy", -0.08],
    ["Yj", -0.08],
    ["vJ", -0.08],
    ["VJ", -0.08],
    ["Vj", -0.08],
    ["vj", -0.08],
    ["WJ", -0.08],
    ["wJ", -0.08],
    ["YC", -0.05],
    ["CY", -0.05],
    ["YG", -0.05],
    ["GY", -0.05],
    ["LY", -0.25],
    ["OY", -0.05],
    ["YO", -0.05],
    ["QY", -0.05],
    ["YQ", -0.05],
    ["PY", -0.05],
    ["aY", -0.1],
    ["Ya", -0.1],
    ["Yb", -0.1],
    ["cY", -0.1],
    ["Yc", -0.1],
    ["dY", -0.1],
    ["eY", -0.1],
    ["Ye", -0.1],
    ["fY", -0.08],
    ["Yg", -0.1],
    ["Ym", -0.1],
    ["nY", -0.1],
    ["Yn", -0.1],
    ["oY", -0.1],
    ["Yo", -0.1],
    ["pY", -0.1],
    ["Yp", -0.1],
    ["qY", -0.1],
    ["Yq", -0.1],
    ["sY", -0.1],
    ["Ys", -0.1],
    ["uY", -0.08],
    ["Yu", -0.08],
    ["vY", -0.08],
    ["Yv", -0.08],
    ["Yw", -0.08],
    ["Yz", -0.08],
    ["ra", -0.03],
    ["re", -0.03],
    ["rs", -0.03],
    ["rq", -0.03],
    ["Le", -0.12],
    ["Lo", -0.12],
    ["Lq", -0.12],
    ["La", -0.12],
    ["Ld", -0.12],
];

// Adjustment untuk satu posisi (non kumulatif)
function get_pair_adjustment_single(pos, i=0) =
    pos <= 0 ? 0 :
    let(pair = str(text[pos-1], text[pos]))
    (
        i >= len(kerning_pairs) ? 0 :
        pair == kerning_pairs[i][0]
            ? font_size * spacing * kerning_pairs[i][1]
            : get_pair_adjustment_single(pos, i+1)
    );

// Kerning kumulatif sampai posisi sekarang
function get_pair_adjustment(pos) =
    pos < 0 ? 0 :
    get_pair_adjustment(pos - 1)
    + get_pair_adjustment_single(pos);

function get_first_letter_offset() =
in_string(text[0], 
    "TY" ) ? -3 : 
in_string(text[0], 
    "vVy" ) ? -1 : 0;
/* ========================================================= */
/* ===================== RENDER ============================ */
/* ========================================================= */

// Ring
// Ring 2/3 (clipped on right side)
if (show_ring) {

    ring_x_pos = -keychainHoleOffset - 2;
    ring_y_pos = 5;

    difference() {

        // Donut
        difference() {
            translate([ring_x_pos, ring_y_pos, 0.75])
                cylinder(h = ring_height, d = keychainHoleSize + 5);

            translate([ring_x_pos, ring_y_pos,  0.75])
                cylinder(h = ring_height + 0.75, d = keychainHoleSize);
        }

        // Cut right side (biar jadi 2/3)
        translate([ring_x_pos +1.8, ring_y_pos - 20, -2])
            cube([20, 40, ring_height + 4]);
    }
}

// Letters
scale([0.45, 0.45, 0.45]) 
for (i = [0 : len(text) - 1]) {

    pos_x =
    (use_auto_spacing
        ? get_auto_spacing(i - 1)
        : i * font_size * spacing)
    + get_cumulative_adjustment(i)
    + get_pair_adjustment(i);

    // Main letter
    color(get_color_rgb(general_color))
    translate([pos_x, 0, 0])
    linear_extrude(height = get_height(i))
    text(
        text[i],
        font = font,
        size = font_size,
        kerning = true
    );

    // Connector for lowercase "i"
    if (enable_i_connector && is_lowercase_i(text[i])) {

        translate([
            pos_x + font_size * (text[i] == "i" ? 0.05 : 0.075),
            18,
            0
        ])
        color(get_color_rgb(general_color))
        cube([
            i_connector_width,
            i_connector_depth,
            font_size * 0.25   // intentionally tall to reach dot
        ]);
    }
}