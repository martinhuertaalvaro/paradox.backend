// editar campos para un id

UPDATE BBDD.TABLA 
SET x = 'Catarroja', y = 'Developer'
WHERE id = 6;

//crear registro

INSERT INTO BBDD.TABLA ( //campos ) VALUES ( //valores );
INSERT INTO bbddparadox.tenant ( name,code,active ) VALUES ( 'PARADOX' ,'paradox',true);