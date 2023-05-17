import uvicorn
from fastapi import FastAPI
from pydantic import BaseModel

import sympy
from sympy.parsing.latex import parse_latex


class Item(BaseModel):
    expr1: str
    expr2: str


app = FastAPI()


@app.post("/compare")
async def create_compare(expr: Item):
    expr.expr1 = parse_latex(expr.expr1)
    expr.expr2 = parse_latex(expr.expr2)
    simplified_expr1 = sympy.simplify(expr.expr1)
    simplified_expr2 = sympy.simplify(expr.expr2)
    if simplified_expr1 == simplified_expr2:
        result = 1
    else:
        result = 0

    return {"result": result}


if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=9000)
