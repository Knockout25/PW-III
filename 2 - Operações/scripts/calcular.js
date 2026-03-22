function calcular() {

    // Obtém os valores dos campos de entrada e converte para números
    const n1 = Number(document.getElementById("A").value);
    const n2 = Number(document.getElementById("B").value);
    const n3 = Number(document.getElementById("C").value);
    const n4 = Number(document.getElementById("D").value);

    // Paridade
    const paridadeA = (n1 % 2 === 0) ? "PAR" : "ÍMPAR";
    const paridadeB = (n2 % 2 === 0) ? "PAR" : "ÍMPAR";
    const paridadeC = (n3 % 2 === 0) ? "PAR" : "ÍMPAR";
    const paridadeD = (n4 % 2 === 0) ? "PAR" : "ÍMPAR";

    document.getElementById("paridadeA").textContent = paridadeA;
    document.getElementById("paridadeB").textContent = paridadeB;
    document.getElementById("paridadeC").textContent = paridadeC;
    document.getElementById("paridadeD").textContent = paridadeD;

    // Dobro
    const dobroA = n1 * 2;
    const dobroB = n2 * 2;
    const dobroC = n3 * 2;
    const dobroD = n4 * 2;

    document.getElementById("dobroA").textContent = dobroA;
    document.getElementById("dobroB").textContent = dobroB;
    document.getElementById("dobroC").textContent = dobroC;
    document.getElementById("dobroD").textContent = dobroD;

    // Triplo
    const triploA = n1 * 3;
    const triploB = n2 * 3;
    const triploC = n3 * 3;
    const triploD = n4 * 3;

    document.getElementById("triploA").textContent = triploA;
    document.getElementById("triploB").textContent = triploB;
    document.getElementById("triploC").textContent = triploC;
    document.getElementById("triploD").textContent = triploD;

    // Quadruplo
    const quadruploA = n1 * 4;
    const quadruploB = n2 * 4;
    const quadruploC = n3 * 4;
    const quadruploD = n4 * 4;

    document.getElementById("quadruploA").textContent = quadruploA;
    document.getElementById("quadruploB").textContent = quadruploB;
    document.getElementById("quadruploC").textContent = quadruploC;
    document.getElementById("quadruploD").textContent = quadruploD;

    // Quintúplo
    const quintuploA = n1 * 5;
    const quintuploB = n2 * 5;
    const quintuploC = n3 * 5;
    const quintuploD = n4 * 5;

    document.getElementById("quintuploA").textContent = quintuploA;
    document.getElementById("quintuploB").textContent = quintuploB;
    document.getElementById("quintuploC").textContent = quintuploC;
    document.getElementById("quintuploD").textContent = quintuploD;

    // Sextúplo
    const sextuploA = n1 * 6;
    const sextuploB = n2 * 6;
    const sextuploC = n3 * 6;
    const sextuploD = n4 * 6;

    document.getElementById("sextuploA").textContent = sextuploA;
    document.getElementById("sextuploB").textContent = sextuploB;
    document.getElementById("sextuploC").textContent = sextuploC;
    document.getElementById("sextuploD").textContent = sextuploD;

    // Quadrado
    const quadradoA = n1 ** 2;
    const quadradoB = n2 ** 2;
    const quadradoC = n3 ** 2;
    const quadradoD = n4 ** 2;

    document.getElementById("quadradoA").textContent = quadradoA;
    document.getElementById("quadradoB").textContent = quadradoB;
    document.getElementById("quadradoC").textContent = quadradoC;
    document.getElementById("quadradoD").textContent = quadradoD;

    // Cubo
    const cuboA = n1 ** 3;
    const cuboB = n2 ** 3;
    const cuboC = n3 ** 3;
    const cuboD = n4 ** 3;

    document.getElementById("cuboA").textContent = cuboA;
    document.getElementById("cuboB").textContent = cuboB;
    document.getElementById("cuboC").textContent = cuboC;
    document.getElementById("cuboD").textContent = cuboD;

    // Quarta potência
    const quartaPotenciaA = n1 ** 4;
    const quartaPotenciaB = n2 ** 4;
    const quartaPotenciaC = n3 ** 4;
    const quartaPotenciaD = n4 ** 4;

    document.getElementById("quartaPotenciaA").textContent = quartaPotenciaA;
    document.getElementById("quartaPotenciaB").textContent = quartaPotenciaB;
    document.getElementById("quartaPotenciaC").textContent = quartaPotenciaC;
    document.getElementById("quartaPotenciaD").textContent = quartaPotenciaD;

    // Quinta potência
    const quintaPotenciaA = n1 ** 5;
    const quintaPotenciaB = n2 ** 5;
    const quintaPotenciaC = n3 ** 5;
    const quintaPotenciaD = n4 ** 5;

    document.getElementById("quintaPotenciaA").textContent = quintaPotenciaA;
    document.getElementById("quintaPotenciaB").textContent = quintaPotenciaB;
    document.getElementById("quintaPotenciaC").textContent = quintaPotenciaC;
    document.getElementById("quintaPotenciaD").textContent = quintaPotenciaD;

    // Sexta potência
    const sextaPotenciaA = n1 ** 6;
    const sextaPotenciaB = n2 ** 6;
    const sextaPotenciaC = n3 ** 6;
    const sextaPotenciaD = n4 ** 6;

    document.getElementById("sextaPotenciaA").textContent = sextaPotenciaA;
    document.getElementById("sextaPotenciaB").textContent = sextaPotenciaB;
    document.getElementById("sextaPotenciaC").textContent = sextaPotenciaC;
    document.getElementById("sextaPotenciaD").textContent = sextaPotenciaD;

    // Bhaskara
    const a = n1;
    const b = n2;
    const c = n3;
    const raiz1 = (-b + Math.sqrt(b ** 2 - 4 * a * c)) / (2 * a);
    const raiz2 = (-b - Math.sqrt(b ** 2 - 4 * a * c)) / (2 * a);
    const delta = b ** 2 - 4 * a * c;

    const bhaskara = `x₁ = ${raiz1.toFixed(2)} | x₂ = ${raiz2.toFixed(2)} | Δ = ${delta.toFixed(2)}`;

    document.getElementById("bhaskara").textContent = bhaskara;

    // Média aritmética
    const mediaAritmetica = (n1 + n2 + n3 + n4) / 4;
    document.getElementById("mediaAritmetica").textContent = mediaAritmetica.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    // Executa uma vez quando a página carrega
    document.getElementById('A').value = 0;
    document.getElementById('B').value = 0;
    document.getElementById('C').value = 0;
    document.getElementById('D').value = 0;
    calcular();

    // Executa sempre que qualquer input mudar
    ['A', 'B', 'C', 'D'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', calcular);
    });
});

function limpar() {
    document.getElementById('A').value = 0;
    document.getElementById('B').value = 0;
    document.getElementById('C').value = 0;
    document.getElementById('D').value = 0;
    calcular();
}