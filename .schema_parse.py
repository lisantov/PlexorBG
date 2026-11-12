import xml.etree.ElementTree as ET

tree = ET.parse('/tmp/schema_dec.xml')
mxfile = tree.getroot()

for diagram in mxfile.findall('diagram'):
    model = diagram.find('.//mxGraphModel')
    if model is None:
        continue
    root = model.find('root')
    cells = {c.get('id'): c for c in root.findall('mxCell')}

    def style(c):
        return c.get('style') or ''

    def y_of(c):
        geo = c.find('mxGeometry')
        return float(geo.get('y') or 0) if geo is not None else 0

    print('=== TABLES ===')
    for cid, c in cells.items():
        if 'shape=table' in style(c) and c.get('parent') == '1':
            print('\n##', c.get('value'))
            rows = [r for r in root.findall('mxCell') if r.get('parent') == cid and 'shape=tableRow' in style(r)]
            rows.sort(key=y_of)
            for row in rows:
                parts = [p.get('value') for p in root.findall('mxCell') if p.get('parent') == row.get('id')]
                parts.sort(key=lambda p: 0)
                # preserve xml order: marker then name
                print('   ', ' | '.join((p or '').strip() for p in parts))

    print('\n=== EDGES ===')
    def label(i):
        if i is None:
            return 'NONE(geo)'
        cell = cells.get(i)
        if cell is None:
            return i
        if 'shape=tableRow' in style(cell):
            tbl = cells.get(cell.get('parent'))
            names = [p.get('value') for p in root.findall('mxCell') if p.get('parent') == i and p.get('value')]
            col = ' '.join(n for n in names if 'PK' not in n and 'FK' not in n)
            return '%s.%s' % (tbl.get('value') if tbl is not None else '?', col)
        if 'shape=table' in style(cell):
            return cell.get('value')
        return cell.get('value') or i
    for cid, c in cells.items():
        if c.get('edge') == '1':
            print('   ', label(c.get('source')), '-->', label(c.get('target')), '| raw', c.get('source'), '->', c.get('target'))
