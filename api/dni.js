export default async function handler(req, res) {
  // Configuración de cabeceras CORS
  res.setHeader('Access-Control-Allow-Credentials', true);
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET,OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version');

  if (req.method === 'OPTIONS') {
    res.status(200).end();
    return;
  }

  const { dni } = req.query;

  if (!dni || dni.length !== 8) {
    return res.status(400).json({ error: 'El DNI debe tener 8 dígitos.' });
  }

  const apiKey = '948485d617fa5e7914d3d2a1161ee897';

  try {
    const apiResponse = await fetch(`https://peruapi.com/api/dni/${dni}`, {
      method: 'GET',
      headers: {
        'X-API-KEY': apiKey,
        'Accept': 'application/json'
      }
    });

    const data = await apiResponse.json();
    return res.status(apiResponse.status).json(data);
  } catch (error) {
    return res.status(500).json({ error: 'Error interno conectando a PeruAPI: ' + error.message });
  }
}