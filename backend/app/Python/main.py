from fastapi import FastAPI
from pydantic import BaseModel
import json

import sympy

class Item(BaseModel):
    expr1: str
    expr2: str 

app = FastAPI()

@app.get("/hello")
def read_root():
    return {"result": "Hello world"}


@app.get("/porovnaj")
async def read_porovnaj():
    s = sympy.Symbol('s')
    expr1 = 2/(s+4)
    expr2 = 4/(2*s+6)
    simplified_expr1 = sympy.simplify(expr1)
    simplified_expr2 = sympy.simplify(expr2)
    if simplified_expr1 == simplified_expr2:
        result = 1
    else:
        result = 0
    return {"result":result}  


@app.post("/porovnaj2")
async def create_porovnaj2(expr:Item):
    #item_dict = item.dict()
    #async def read_porovnaj2(expr1:str="2/(s+4)",expr2:str="4/(2*s+8)"):
    # s = sympy.Symbol('s')
    # expr1 = eval(expr1)
    # expr2 = eval(expr2)
    simplified_expr1 = sympy.simplify(expr.expr1)
    simplified_expr2 = sympy.simplify(expr.expr2)
    if simplified_expr1 == simplified_expr2:
        result = 1
    else:
        result = 0
    return {"result":result} 
    #return expr
