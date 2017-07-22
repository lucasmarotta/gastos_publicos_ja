@ECHO OFF
lessc "less/main.less" "css/main.min.css" --clean-css && lessc "less/fonts.less" "css/fonts.min.css" --clean.css
