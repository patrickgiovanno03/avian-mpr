// Parameters
$fn = 100;
text = "1234567890"; // Change this to the desired text
line2 = "Line2"; // Change this to the desired second line text
2ndline = false;
facedownmode = false;
fontSize = 14.5;       // Font size for the text
line2Offset = 0;    // Offset for the second line horizontally
line2VerticalOffset = -15; // Vertical offset for the second line
thickness = 4.5;       // Thickness of the base
textThickness = 1.5;

keychainHoleSize = 4.5; // Diameter of the keychain hole
keychainHoleOffset = -1.8;// Offset of the keychain hole from the left
// Radius of edge, Increase to fill in gaps
r = 2;

// Box customization parameters
boxWidth = 0;      // Width of the box
boxHeight = 0;     // Height of the box
boxXOffset = 0;     // Horizontal offset for the box
boxYOffset = -30;   // Vertical offset for the box

Font = "Pokemon Solid"; // [Inter, Rubik, Open Sans, Inter Tight, Source Sans 3, Noto Emoji, Ubuntu Sans, Roboto Slab, Plus Jakarta Sans, Roboto Serif, HarmonyOS Sans, Roboto Flex, Roboto Mono, Playfair Display, Merriweather Sans, Noto Sans SC, Work Sans, Ubuntu Sans Mono, Raleway, Nunito Sans, Montserrat, Roboto, Roboto Condensed, Open Sans Condensed, Oswald, Noto Sans, Nunito]
FontStyle = "Regular"; // [Black Italic, Thin, Bold, Medium, Thin Italic, Regular, Medium Italic, Bold Italic, ExtraBold Italic, ExtraBold, Light Italic, SemiBold Italic, Light, ExtraLight Italic, ExtraLight, SemiBold, Black, Italic]
font = str(Font , ":style=", FontStyle);

function in_string(c, s) = len(search(c, s)) > 0;

function char_width(c) =
    in_string(c, 
    "il" ) ? 0.6
    : in_string(c, 
    "1" ) ? 0.7
    : in_string(c, 
    "nhI3" ) ? 1.1
    : in_string(c, 
    "PKBDUTGHQRWXkx" ) ? 1.2
    : in_string(c, 
    "MOVJY" ) ? 1.4
    : in_string(c, 
    "m" ) ? 1.6
    : in_string(c, 
    "w" ) ? 1.8
    : 1; // default
    
function text_offset(txt, spacing, size, i) =
    i == 0 ? 0 :
    text_offset(txt, spacing, size, i - 1) +
    char_width(txt[i - 1]) * spacing;
    
module spaced_text(txt, spacing, size, font) {
    for (i = [0 : len(txt) - 1]) {
        translate([text_offset(txt, spacing, size, i), 0, 0])
            text(txt[i], size = size, valign = "center", halign = "left", font = font);
    }
}
module spaced_text2(txt, spacing, size, font) {
    for (i = [0 : len(txt) - 1]) {
        if (i % 2 != 0) {
        translate([text_offset(txt, spacing, size, i), 0, 0])
                text(txt[i], size = size, valign = "center", halign = "left", font = font);
        }
    }
}
module keychain(text, line2, fontSize, thickness, textThickness, keychainHoleSize, keychainHoleOffset, font, 2ndline, line2Offset, line2VerticalOffset, boxWidth, boxHeight, boxXOffset, boxYOffset) {
    // Create the background "bubble" for the first line
    difference () {
        difference(){
    translate([0, 0, 0])
        linear_extrude(height = thickness)
            offset(r = r)
                spaced_text(text, fontSize * 0.8, fontSize, font);
difference() {
              translate([0, 0, 3])
        linear_extrude(height = thickness + 1)
            spaced_text(text, fontSize * 0.8, fontSize, font);
    translate([0, 0, 0])
        linear_extrude(height = thickness+5)
            offset(r = r)
                spaced_text2(text, fontSize * 0.8, fontSize, font);
} 
    }
translate([0, 0, 3])
        linear_extrude(height = thickness + 1)
            spaced_text2(text, fontSize * 0.8, fontSize, font);
    }
    
    // Add the customizable box
    translate([boxXOffset, boxYOffset, 0])
        linear_extrude(height = thickness)
            square([boxWidth, boxHeight], center = false);

    difference() {
        // Add the keychain hole
        union() {
            translate([-keychainHoleOffset - 3, 0, 0]) {
                cylinder(h = 2.5, d = keychainHoleSize + 5, center = false);
            }
            translate([-keychainHoleOffset - 4, -keychainHoleSize / 2 - 1.25, 0]){
                cube(size = [7 + keychainHoleOffset, keychainHoleSize + 2.5, 2.5], center = false);
            }
        }
        translate([-keychainHoleOffset - 3, 0, 0]) {
            cylinder(h = thickness, d = keychainHoleSize, center = false);
        }
    }
}

// Main call
keychain(text, line2, fontSize, thickness, textThickness, keychainHoleSize, keychainHoleOffset, font, 2ndline, line2Offset, line2VerticalOffset, boxWidth, boxHeight, boxXOffset, boxYOffset);