# Registro de cambios · TaskBoard API

## 2026-11-14

### Corregido
- MT-013: GET /api/transacciones responde 422 si ?estado no es Iniciada, Completada o Fallida.
  ⚠ Clientes que enviaban estados mal escritos ahora recibirán 422.

### Agregado
- MT-014: GET /api/transacciones/filtrar acepta los parámetros opcionales monto_min y monto_max para filtrar transacciones por rango de monto.
- Los parámetros se validan como numéricos mayores o iguales a 0.
- monto_max debe ser mayor o igual que monto_min cuando ambos están presentes.
- El comportamiento existente del filtro por estado y fechas se mantiene sin cambios.