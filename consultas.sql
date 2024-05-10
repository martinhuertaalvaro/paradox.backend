// editar campos para un id

UPDATE BBDD.TABLA 
SET x = 'Catarroja', y = 'Developer'
WHERE id = 6;

//crear registro

INSERT INTO BBDD.TABLA ( //campos ) VALUES ( //valores );
INSERT INTO bbddparadox.tenant ( name,code,active ) VALUES ( 'PARADOX' ,'paradox',true);
INSERT INTO bbddparadox.user ( tenant_id_id,email,roles,active,password,name,surname,birthdate,city,workfield,registerdate) VALUES ( 1 ,'amartinh@paradox.com','["ROLE_ADMIN"]',true,'$2y$13$PomIFMs7SSFNBPbESW3oZevUL90GTvxwZXOanT7gpnLtVZr4aEyDK','Álvaro','Martín','2004/11/24','Valencia','Developer','2024/04/04');