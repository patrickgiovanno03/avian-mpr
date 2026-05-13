// Created by Alberto Nicas (March 2024)
// Custom Text Keychain Chain v1.0

/* [General] */
// Text to display
text = "AURORAORAA";

// Font selector
selected_font = "DynaPuff:style=Bold"; // [Archivo Black, Bangers, Bungee, Changa One, Bebas Neue, Poppins Black, Sedgwick Ave Display]

// Letter size
letter_size = 11;

// Letter height (positive = raised text, negative = engraved text)
letter_height = 1; // [-12:0.1:5]

// Include ring on first element?
ring_first_element = true; // [true:Yes, false:No]

// Base color
base_color = "Pink"; // [Yellow, Blue, Light Blue, Dark Blue, Beige, White, Gray, Brown, Purple, Orange, Black, Pink, Red, Turquoise, Green, Light Green, Dark Green]

// Letter color
letter_color = "White"; // [Yellow, Blue, Light Blue, Dark Blue, Beige, White, Gray, Brown, Purple, Orange, Black, Pink, Red, Turquoise, Green, Light Green, Dark Green]

/* [Base Settings] */
height = 6;    // [6:0.5:12] Main rectangle height
width = 17;  
length = 18;

/* [Hidden] */
hole_diameter = 10;
hole_depth = 5.5;
rod_diameter = 2;
space_between_models = 1;
outer_cylinder_diameter = 6;
inner_cylinder_diameter = 3;
cylinder_height = 4;
cylinder_separation = 2;
support_height = 1;
support_length = 2;
chamfer_size = 1; // Chamfer size
/* [Flower Shape Settings] */
center_radius = 15;
petal_radius = 12;
petal_count = 5;
petal_distance = 14;
rounding_radius = 1;

// Function to calculate the length of an individual element
function calculate_element_length(is_first) = 
    (is_first && !ring_first_element) ? (length - 2) : length;

// Function to calculate total model length
function calculate_total_length() =
    calculate_element_length(true) + 
    (len(text) - 1) * (length + space_between_models);

// Module to create individual model
module flower_profile_2d() {
    offset(r = rounding_radius) {
        union() {
            // Center
            circle(r = center_radius - rounding_radius);
            
            // Petals
            angle_step = 360 / petal_count;
            for (i = [0 : petal_count - 1]) {
                rotate([0, 0, i * angle_step])
                    translate([petal_distance, 0, 0])
                        circle(r = petal_radius - rounding_radius);
            }
        }
    }
}

module individual_model(letter, is_last = false, is_first = false) {
    
    current_length = (is_first && !ring_first_element) ? length - 2 : length;
    
    text_x = is_first && !ring_first_element ? 
        current_length/2 + 0.6 :
        length/2 + 1;
    
    difference() {
        
        color(base_color) {
            difference() {
                flower_size = petal_distance * 2 + petal_radius * 2;
flower_scale = 1.1; // 🔥 ini kuncinya

linear_extrude(height = height)
    translate([8.5, 8.5, 0])
        scale([
            (current_length / flower_size) * flower_scale,
            (width / flower_size) * flower_scale
        ])
            flower_profile_2d();
                
                // Bottom-left chamfer
                translate([-1, 0, 0])
                    rotate([0, 45, 0])
                        cube([chamfer_size * sqrt(2), width, chamfer_size * sqrt(2)]);
                
                // Top-left chamfer
                translate([0, 0, height+1])
                    rotate([0, 135, 0])
                        cube([chamfer_size * sqrt(2), width, chamfer_size * sqrt(2)]);
                
                if (!is_first || ring_first_element) {
                    translate([0, width/2, height/2])
                        rotate([90, 0, 0])
                            translate([0, 0, -hole_depth/2])
                                cylinder(h=hole_depth, d=hole_diameter, $fn=50);
                }
            }

            if (!is_first || ring_first_element) {
                rod_length = width * 0.5; // 🔥 lebih pendek

translate([rod_diameter/2, (width - rod_length)/2, height/2])
    rotate([-90, 0, 0])
        cylinder(h=rod_length, d=rod_diameter, $fn=50);
            }

            if (!is_last) {
                translate([current_length + cylinder_separation, width/2 + cylinder_height/2, height/2]) {
                    rotate([90, 0, 0])
                        difference() {
                            cylinder(h=cylinder_height, d=outer_cylinder_diameter, $fn=50);
                            translate([0, 0, -0.1])
                                cylinder(h=cylinder_height + 0.2, d=inner_cylinder_diameter, $fn=50);
                        }
                }

                translate([current_length, width/2 - cylinder_height/2, height/2 + outer_cylinder_diameter/2 - support_height])
                    cube([support_length, cylinder_height, support_height]);

                translate([current_length, width/2 - cylinder_height/2, height/2 - outer_cylinder_diameter/2])
                    cube([support_length, cylinder_height, support_height]);
            }
        }
        
        if (letter_height < 0) {
            color(letter_color)
                translate([text_x, width/2, height + letter_height])
                    linear_extrude(height = abs(letter_height) + 0.01)
                        text(letter, 
                             size = letter_size,
valign = "center",
halign = "center",
font = selected_font);
        }
    }
    
    if (letter_height > 0) {
        color(letter_color)
        translate([text_x, width/2, height])
        linear_extrude(height = letter_height)
        text(letter, 
        size = letter_size,
        valign = "center",
        halign = "center",
        font = selected_font);
    }
}

// Calculate X position
function calculate_position(i) = 
    i == 0 ? 0 :
    ((!ring_first_element ? (length - 2) : length) +
    (i - 1) * (length + space_between_models) +
    space_between_models);

// Center offset
x_offset = -calculate_total_length() / 2;

// Generate models centered
translate([x_offset, -width/2, 0])
    for (i = [0:len(text) - 1]) {
        translate([calculate_position(i), 0, 0])
            individual_model(str(text[i]), 
            i == len(text) - 1,
            i == 0);
    }