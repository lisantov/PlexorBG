import xml.etree.ElementTree as ET

t = ET.parse('/tmp/schema_dec.xml')
root = t.getroot().find('.//mxGraphModel/root')
cells = {c.get('id'): c for c in root.findall('mxCell')}


def info(i):
    c = cells.get(i)
    if c is None:
        return 'MISSING ' + str(i)
    p = cells.get(c.get('parent'))
    vals = [x.get('value') for x in root.findall('mxCell') if x.get('parent') == i and x.get('value')]
    return (i, 'parent=' + str(p.get('value') if p is not None else c.get('parent')), 'vals=' + str(vals))


for i in ['6wnjIVXl8KeQYk-532Zi-36', '6wnjIVXl8KeQYk-532Zi-97', '6wnjIVXl8KeQYk-532Zi-138',
          '6wnjIVXl8KeQYk-532Zi-2', 'vY8S7A0I6mVYKwUC6ZLf-8', '6wnjIVXl8KeQYk-532Zi-191',
          '6wnjIVXl8KeQYk-532Zi-161', 'QB6L8uFPZx5ITirVI--m-8', '6wnjIVXl8KeQYk-532Zi-158']:
    print(info(i))

print('--- geometry of table cells ---')
for cid, c in cells.items():
    st = c.get('style') or ''
    if 'shape=table' in st and c.get('parent') == '1':
        g = c.find('mxGeometry')
        print(c.get('value'), g.get('x'), g.get('y'), g.get('width'), g.get('height'))
