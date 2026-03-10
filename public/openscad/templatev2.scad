// Parameters
$fn = 100;
text = "Jo"; // Change this to the desired text
line2 = "Line2"; // Change this to the desired second line text
2ndline = false;
facedownmode = false;
fontSize = 15;       // Font size for the text
line2Offset = 0;    // Offset for the second line horizontally
line2VerticalOffset = -15; // Vertical offset for the second line
thickness = 4;       // Thickness of the base
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

Font = "Luckiest Guy"; // [Inter, Rubik, Open Sans, Inter Tight, Source Sans 3, Noto Emoji, Ubuntu Sans, Roboto Slab, Plus Jakarta Sans, Roboto Serif, HarmonyOS Sans, Roboto Flex, Roboto Mono, Playfair Display, Merriweather Sans, Noto Sans SC, Work Sans, Ubuntu Sans Mono, Raleway, Nunito Sans, Montserrat, Roboto, Roboto Condensed, Open Sans Condensed, Oswald, Noto Sans, Nunito]
FontStyle = "Black Italic"; // [Black Italic, Thin, Bold, Medium, Thin Italic, Regular, Medium Italic, Bold Italic, ExtraBold Italic, ExtraBold, Light Italic, SemiBold Italic, Light, ExtraLight Italic, ExtraLight, SemiBold, Black, Italic]
font = str(Font , ":style=", FontStyle);

module keychain(text, line2, fontSize, thickness, textThickness, keychainHoleSize, keychainHoleOffset, font, 2ndline, line2Offset, line2VerticalOffset, boxWidth, boxHeight, boxXOffset, boxYOffset) {
    // Create the background "bubble" for the first line
    translate([0, 0, 0])
        linear_extrude(height = thickness)
            offset(r = r)
                text(text, size = fontSize, valign = "center", halign = "left", font = font);

    // Create the background "bubble" for the second line if 2ndline is true
    if (2ndline) {
        translate([line2Offset, line2VerticalOffset, 0])
            linear_extrude(height = thickness)
                offset(r = r)
                    text(line2, size = fontSize, valign = "center", halign = "left", font = font);
    }

    // Extrude the text for the first line
    if (facedownmode){
          translate([0, 0, thickness])
        color([0,0,0])linear_extrude(height = 0.1)
            text(text, size = fontSize, valign = "center", halign = "left", font = font);  
    } else{
           translate([0, 0, thickness])
        color([0,0,0])linear_extrude(height = textThickness)
            text(text, size = fontSize, valign = "center", halign = "left", font = font);
    }

    // Extrude the text for the second line if 2ndline is true
    if (2ndline) {
        
        if(facedownmode){
            color([0,0,0])translate([line2Offset, line2VerticalOffset, thickness])
            linear_extrude(height = 0.1)
                text(line2, size = fontSize, valign = "center", halign = "left", font = font);
        }else{
            color([0,0,0])translate([line2Offset, line2VerticalOffset, thickness])
            linear_extrude(height = textThickness)
                text(line2, size = fontSize, valign = "center", halign = "left", font = font);
        }
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