# ScreenRecall

a script to save all windows of the Linux desktop as screenshots at regular intervals and make them searchable

## installation

* clone the repo
* install requirements
* call run.php
* open http://localhost:4000

### requirements

```
sudo apt install php-cli imagemagick wmctrl tesseract-ocr
```

### configure

copy the settings.json.dist into settings.json and configure

```
{
  "screenshots": "/WHERE/TO/STORE/screenshots",
  "interval": 20,
  "port": 4000,
  "exclude": [
    "Mattermost",
    "Betterbird",
    "Buddy-List",
    "ScreenRecall",
    "Privater Modus",
    "Inkognitortab
  ]
}
```

### start

```
php run.php
```

### view and search

open http://localhost:4000