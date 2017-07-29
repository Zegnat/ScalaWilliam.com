import lxml
import os
from lxml import html
file_root = os.getenv("SOURCE_ROOT", ".")
def lstripped(lines):
    shift = min(
        [ len(line) - len(line.lstrip()) if len(line) != 0 else 999 for line in lines]
    )
    for line in lines:
        if len(line) == 0:
            yield line
        else:
            yield line[shift:]

h = html.parse("index.html")
for item in h.xpath("//code[@data-source and @data-from and @data-to]"):
    source = item.attrib['data-source']
    line_from = int(item.attrib['data-from'])
    line_to = int(item.attrib['data-to'])
    item.getparent().attrib['class'] = 'language-scala'
    with open(file_root + "/" + source) as f:
        lines = f.read().split("\n")[(line_from-1):line_to]
        item.text = "\n".join(lstripped(lines))
outString = html.tostring(h, method = 'html')
with open('index.html', 'wb') as f:
    f.write(outString)
