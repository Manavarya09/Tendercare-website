#!/bin/bash

# Create optimized directory if it doesn't exist
mkdir -p assets/img/optimized

# Function to convert images to WebP
convert_to_webp() {
    local input_file="$1"
    local output_file="${input_file%.*}.webp"
    
    # Skip if WebP already exists and is newer than the original
    if [ -f "$output_file" ] && [ "$output_file" -nt "$input_file" ]; then
        echo "Skipping $input_file - WebP already exists and is up to date."
        return
    fi
    
    echo "Converting $input_file to WebP..."
    cwebp -q 80 "$input_file" -o "$output_file"
    
    # Optimize the original image
    if [[ "$input_file" == *.jpg ]] || [[ "$input_file" == *.jpeg ]]; then
        jpegoptim --strip-all "$input_file"
    elif [[ "$input_file" == *.png ]]; then
        optipng -o7 -strip all "$input_file"
    fi
}

# Find and process all images
export -f convert_to_webp
find assets/img -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) \
    -not -path "*/optimized/*" \
    -exec bash -c 'convert_to_webp "$0"' {} \;

echo "Image optimization complete!"
