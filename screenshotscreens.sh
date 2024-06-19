#!/bin/bash

if [ "$3" = "run" ]; then
  while true; do
      screenshotscreens.sh $1 $4
      sleep $2
  done
  exit 0
fi

# Function to compare images
compare_images() {
    local img1=$1
    local img2=$2
    compare -metric AE "$img1" "$img2" null: 2>&1
}

# Function to check if a window title contains any excluded words
should_exclude_window() {
    local window_title=$1
    for word in "${exclude_words[@]}"; do
        if [[ "$window_title" == *"$word"* ]]; then
            return 0
        fi
    done
    return 1
}

# Get the current desktop number
current_desktop=$(wmctrl -d | grep '\*' | awk '{print $1}')

# Get the list of all windows on the current desktop
#windows=$(wmctrl -l | awk -v desktop="$current_desktop" '$2 == desktop {print $1}')
windows=$(wmctrl -l | awk -v desktop="$current_desktop" '$2 == desktop {print $1, $0}')

# Create a base directory for screenshots
base_dir=$1
shift
mkdir -p "$base_dir"

IFS=',' read -r -a exclude_words <<< "$1"
shift  # Shift past the excluded words argument


# Create a unique directory for this screenshot session
timestamp=$(date +"%Y%m%d_%H%M%S")
session_dir="$base_dir/session_$timestamp"
mkdir -p "$session_dir"

# Iterate through each window ID and take a screenshot
while IFS= read -r line; do
    window_id=$(echo "$line" | awk '{print $1}')
    window_title=$(echo "$line" | cut -d' ' -f4-)

    # Check if the window title contains any excluded words
    if should_exclude_window "$window_title"; then
        echo "Skipping window $window_id with title '$window_title' due to exclusion"
        continue
    fi
    # Define the output file name using the window ID
    output_file="$session_dir/screenshot_${window_id}.jpg"
    
    # Take a screenshot of the window
    import -window "$window_id" "$output_file"
    
    # Check if a previous screenshot exists for this window
    previous_screenshot=$(find "$base_dir" -name "screenshot_${window_id}.jpg" | grep -v "$session_dir" | sort | tail -n 1)
    
    if [ -n "$previous_screenshot" ]; then
        # Compare the new screenshot with the previous one
        if [ "$(compare_images "$output_file" "$previous_screenshot")" -eq 0 ]; then
            # If they are the same, remove the new screenshot and create a symlink
            rm "$output_file"
            ln -s "$previous_screenshot" "$output_file"
            echo "No change for window $window_id, symlink created to $previous_screenshot"
	    ln -s "${previous_screenshot}.ocr.txt" "${output_file}.ocr.txt"
        else
            echo "Screenshot of window $window_id saved as $output_file"
	          if test -f "$output_file"; then
	            convert "${output_file}" -resize 200% -contrast -normalize "${output_file}.temp.png"
              tesseract "${output_file}.temp.png" "${output_file}.ocr" > /dev/null
              rm -f "${output_file}.temp.png"
		          #tesseract "${output_file}" "${output_file}.ocr" > /dev/null &
	          fi
        fi
    else
        echo "Screenshot of window $window_id saved as $output_file"
	      if test -f "$output_file"; then
            convert "${output_file}" -resize 200% -contrast -normalize "${output_file}.temp.png"
        	  tesseract "${output_file}.temp.png" "${output_file}.ocr" > /dev/null
            rm -f "${output_file}.temp.png"
        	  #tesseract "${output_file}" "${output_file}.ocr" > /dev/null &
        fi
    fi
done <<< "$windows"