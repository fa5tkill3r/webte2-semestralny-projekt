from fastapi import FastAPI
from pydantic import BaseModel

import sympy
from sympy.parsing.latex import parse_latex

import json



class Item(BaseModel):
    expr1: str
    expr2: str 

app = FastAPI(ssl_keyfile="webte.fei.stuba.sk.key", ssl_certfile="webte_fei_stuba_sk.pem")



@app.post("/compare")
async def create_compare(expr:Item):
    expr.expr1 = parse_latex(expr.expr1)
    expr.expr2 = parse_latex(expr.expr2)
    simplified_expr1 = sympy.simplify(expr.expr1)
    simplified_expr2 = sympy.simplify(expr.expr2)
    if simplified_expr1 == simplified_expr2:
        result = 1
    else:
        result = 0
    return {"result":result} 
