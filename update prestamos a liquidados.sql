
/*UPDATE prestamos AS pres SET  pres.status = 'Liquidado'*/
SELECT * FROM prestamos AS pres
WHERE IFNULL((SELECT SUM(ade.pago_total) FROM adeudos AS ade WHERE ade.idPrestamo = pres.id), 0) <= 0
AND pres.status != 'Liquidado'