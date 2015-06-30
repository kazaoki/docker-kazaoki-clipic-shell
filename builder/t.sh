
echo -ne "\e[38;07m"

# for code in {0..255}; do echo -ne "\e[38;05;${code}m $(printf %03d $code)"; [ $((${code} % 16)) -eq 15 ] && echo; done
for code in {0..255}; do echo -ne "\e[38;05;${code}m "; [ $((${code} % 16)) -eq 15 ] && echo; done

echo -ne "\e[38;05;123m "
echo -ne "\e[38;05;124m "

echo -e "\e[m"
